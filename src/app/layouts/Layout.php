<?php declare(strict_types=1);

namespace Src\App\Layouts;

use Exception;
use Oscurlo\ComponentRenderer\Component;
use Src\Config\AppConfig;
use Src\Core\Util;

final class Layout
{
    private array $default_props = [];

    public function __construct()
    {
        $_SESSION["csrf"] = Util::generateCsrfToken();
        $this->default_props = [
            "title" => AppConfig::COMPANY["NAME"],
            "content-title" => AppConfig::COMPANY["NAME"],
            "content-breadcrumb" => AppConfig::CURRENT_ROUTE
        ];
    }

    private array $components = [
        AppConfig::BASE_FOLDER . "/src/app/components" => ["AppMain", "AppHeader", "AppSidebar", "Image"],
    ];

    /**
     * @throws Exception
     */
    public function auth(object $props): string
    {
        $props = (object) [...$this->default_props, ...(array) $props];

        return Component::render(
            Component::template(
                filename: Util::getTemplateBlade(__FILE__),
                vars: ["executed" => __FUNCTION__],
                props: $props
            ),
            $this->components
        );
    }

    /**
     * @throws Exception
     */
    public function system(object $props): string
    {
        $props = (object) [...$this->default_props, ...(array) $props];

        return Component::render(
            Component::template(
                filename: Util::getTemplateBlade(__FILE__),
                vars: ["executed" => __FUNCTION__],
                props: $props
            ),
            $this->components
        );
    }
}