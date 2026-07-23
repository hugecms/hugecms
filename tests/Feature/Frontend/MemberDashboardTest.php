<?php

namespace Tests\Feature\Frontend;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class MemberDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        Role::findOrCreate('member', 'web');
    }

    protected function memberUser(): User
    {
        $user = User::factory()->create();
        $user->assignRole('member');

        return $user;
    }

    public function test_dashboard_requires_authentication(): void
    {
        $response = $this->get('/member');

        $response->assertRedirect('/login');
    }

    public function test_member_can_view_dashboard(): void
    {
        $user = $this->memberUser();

        $response = $this->actingAs($user)->get('/member');

        $response->assertOk();
        $response->assertSee($user->name);
        $response->assertSee($user->email);
    }

    public function test_member_can_update_profile(): void
    {
        $user = $this->memberUser();

        $response = $this->actingAs($user)->put('/member/profile', [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '13800138000',
        ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirectToRoute('member.dashboard');

        $user->refresh();
        $this->assertSame('Updated Name', $user->name);
        $this->assertSame('updated@example.com', $user->email);
        $this->assertSame('13800138000', $user->phone);
    }

    public function test_profile_email_must_be_unique_excluding_self(): void
    {
        $user = $this->memberUser();
        $other = User::factory()->create(['email' => 'other@example.com']);

        $response = $this->actingAs($user)->put('/member/profile', [
            'name' => $user->name,
            'email' => $other->email,
            'phone' => $user->phone,
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_member_can_update_password(): void
    {
        $user = $this->memberUser();

        $response = $this->actingAs($user)->put('/member/password', [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirectToRoute('member.dashboard');

        $this->assertTrue(Hash::check('new-password', $user->refresh()->password));
    }

    public function test_password_update_requires_current_password(): void
    {
        $user = $this->memberUser();

        $response = $this->actingAs($user)->put('/member/password', [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertSessionHasErrors('current_password');
    }

    public function test_member_can_upload_avatar(): void
    {
        Storage::fake('public');

        $user = $this->memberUser();

        $response = $this->actingAs($user)->post('/member/avatar', [
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirectToRoute('member.dashboard');

        $user->refresh();
        $this->assertNotNull($user->getFirstMedia('avatar'));
        $this->assertStringContainsString('avatar', $user->getFirstMediaUrl('avatar', 'thumb'));
    }

    public function test_member_cannot_access_admin_panel(): void
    {
        $user = $this->memberUser();

        $this->actingAs($user)
            ->get('/admin')
            ->assertStatus(403);
    }
}
