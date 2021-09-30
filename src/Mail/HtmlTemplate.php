<?php

namespace Xanweb\HtmlHelper\Mail;

use Concrete\Core\Filesystem\FileLocator;
use Concrete\Core\Localization\Localization;
use Concrete\Core\Support\Facade\Application;

class HtmlTemplate
{
    /**
     * @var string
     */
    private string $filePath;

    /**
     * HtmlTemplate Construct.
     *
     * @param string $htmlFileName
     * @param string|null $pkgHandle
     */
    public function __construct(string $htmlFileName, ?string $pkgHandle = null)
    {
        $app = Application::getFacadeApplication();
        $locator = $app->make(FileLocator::class);
        if ($pkgHandle) {
            $locator->addLocation(new FileLocator\PackageLocation($pkgHandle));
        }

        if (ends_with($htmlFileName, '.html')) {
            $htmlFileName = substr($htmlFileName, 0, -5);
        }

        $lng = Localization::activeLanguage();
        $filePath = implode('/', [
            DIRNAME_MAIL_TEMPLATES,
            'html',
            "{$htmlFileName}_$lng.html",
        ]);

        $r = $locator->getRecord($filePath);
        if ($r === null || !$r->exists()) {
            $filePath = implode('/', [
                DIRNAME_MAIL_TEMPLATES,
                'html',
                "$htmlFileName.html",
            ]);

            $r = $locator->getRecord($filePath);
        }

        if ($r === null || !$r->exists()) {
            throw new \RuntimeException(t('Template File %s not found at path "%s".', $htmlFileName, $filePath));
        }

        $this->filePath = $r->file;
    }

    /**
     * Render Mail Template.
     *
     * @param array $args Eg. ['placeholder-key' => $value]
     * where 'placeholder-key' is represented in HTML file like following: &lt;p&gt;Example: <!--placeholder-key-->&lt;/p&gt;
     *
     * @return string
     */
    public function render(array $args = []): string
    {
        $htmlContent = file_get_contents($this->filePath);

        if ($htmlContent && $args !== []) {
            $placeHolders = array_map(
                static fn ($v) => '<!--' . strtoupper($v) . '-->',
                array_keys($args)
            );

            $htmlContent = str_replace($placeHolders, array_values($args), $htmlContent);
        }

        return $htmlContent?:'';
    }
}
