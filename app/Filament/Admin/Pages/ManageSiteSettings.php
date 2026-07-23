<?php

namespace App\Filament\Admin\Pages;

use App\Support\SiteSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageSiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = '系统设置';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = '站点设置';

    protected static ?string $title = '站点设置';

    protected string $view = 'filament.admin.pages.manage-site-settings';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()->can('setting.manage');
    }

    public function mount(): void
    {
        $this->form->fill([
            'site_name' => SiteSetting::get('site_name'),
            'logo' => SiteSetting::get('logo'),
            'icp' => SiteSetting::get('icp'),
            'copyright' => SiteSetting::get('copyright'),
            'contact' => SiteSetting::get('contact'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                TextInput::make('site_name')
                    ->label('站点名称')
                    ->maxLength(255),
                FileUpload::make('logo')
                    ->label('站点 Logo')
                    ->image()
                    ->directory('site')
                    ->disk('public')
                    ->maxSize(2048),
                TextInput::make('icp')
                    ->label('ICP 备案号')
                    ->maxLength(255),
                Textarea::make('copyright')
                    ->label('版权信息')
                    ->rows(2),
                Textarea::make('contact')
                    ->label('联系信息')
                    ->rows(4),
            ]);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('保存')
                ->action('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            if ($key === 'logo' && is_array($value)) {
                $value = $value[0] ?? null;
            }

            SiteSetting::set($key, $value);
        }
    }
}
