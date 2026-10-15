<?php

declare(strict_types=1);

namespace app\controller;

use Carbon\Carbon;
use Juling\Foundation\Response\JsonResponse;
use Juling\Foundation\Routing\Controller;
use think\facade\Config;
use think\response\View;

abstract class BaseController extends Controller
{
    use JsonResponse;

    /**
     * 模板变量
     */
    protected array $vars = [];

    protected function initialize(): void
    {
        $this->vars = [];
        $this->loadTemplate();
    }

    /**
     * 变量赋值
     */
    protected function assign(string $name, mixed $value): void
    {
        $this->vars = array_merge($this->vars, [$name => $value]);
    }

    /**
     * 获取内容
     */
    protected function fetch(string $template, array $vars = []): string
    {
        return $this->display($template, $vars)->getContent();
    }

    /**
     * 视图渲染
     */
    protected function display(string $template, array $vars = []): View
    {
        if (! empty($vars)) {
            $this->vars = array_merge($this->vars, $vars);
        }

        return view('/'.$template, $this->vars);
    }

    /**
     * 获取当前时间戳
     */
    protected function getCurrentTimestamp(): int
    {
        return Carbon::now()->getTimestamp();
    }

    /**
     * 获取当前毫秒时间戳
     */
    protected function getCurrentMillisecond(): int
    {
        return Carbon::now()->getTimestampMs();
    }

    protected function loadTemplate(): void
    {
        $theme = config('app.default_theme', 'default');

        Config::set([
            'view_path' => public_path(sprintf('/themes/%s/', $theme)),
            'view_suffix' => 'php',
        ], 'view');
    }
}
