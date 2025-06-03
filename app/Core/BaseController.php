<?php
// app/Core/BaseController.php
namespace App\Core;

class BaseController
{

    protected array $siteConfig = [];

    public function __construct()
    {
        $configFile = __DIR__ . '/../../config/app.php';

        if (file_exists($configFile)) {
            $this->siteConfig = require $configFile;
        }
    }

    protected function view(string $viewName, array $data = [], string $layoutName = 'layouts.main'): void {
        // Dados padrão do site que estarão sempre disponíveis no layout e na view
        $baseData = [
            'siteName'     => $this->siteConfig['siteName'] ?? 'Site Padrão',
            'myName'       => $this->siteConfig['siteOwner'] ?? 'Proprietário Padrão',
            'pageTitle'    => $this->siteConfig['defaultPageTitle'] ?? 'Título Padrão',
        ];

        // Mescla os dados base com os dados específicos da página.
        // Dados específicos da página ($data) sobrescrevem os dados base se houver chaves iguais.
        $viewData = array_merge($baseData, $data);

        $viewPath = str_replace('.', '/', $viewName);
        $viewFile = __DIR__ . '/../Views/' . $viewPath . '.php';

        if (file_exists($viewFile)) {
            extract($viewData); // Extrai $siteName, $myName, $pageTitle e os dados de $data
            ob_start();
            require $viewFile;
            $contentForLayout = ob_get_clean();

            $layoutPath = str_replace('.', '/', $layoutName);
            $layoutFile = __DIR__ . '/../Views/' . $layoutPath . '.php';

            if (file_exists($layoutFile)) {
                // As variáveis extraídas de $viewData já estão disponíveis para o layout
                require $layoutFile;
            } else {
                echo "Erro: Arquivo de Layout '$layoutName' não encontrado em '$layoutFile'";
            }
        } else {
            echo "Erro: View '$viewName' não encontrada em '$viewFile'";
        }
    }
}
