<?php

namespace Laravel\Gettext;

use Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

use App;
use Config;
use Input;
use Session;
use Gettext\Extractors;
use Gettext\Generators;
use Gettext\Translations;
use Gettext\Translator;

class Gettext
{
    private static $config = [];
    private static $locale;

    public static function setConfig(array $config)
    {
        self::$config = $config;
    }

    private static function getFile($locale)
    {
        return sprintf('%s/%s/LC_MESSAGES/%s.', self::$config['storage'], $locale, self::$config['domain']);
    }

    private static function getCache($locale)
    {
        if (is_file($file = self::getFile($locale).'po')) {
            return Extractors\Po::fromFile($file);
        }

        return false;
    }

    private static function store($locale, $entries)
    {
        $file = self::getFile($locale);
        $dir = dirname($file);

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        Generators\Mo::toFile($entries, $file.'mo');
        Generators\Po::toFile($entries, $file.'po');
        Generators\PhpArray::toFile($entries, $file.'php');

        return $entries;
    }

    private static function scan()
    {
        Extractors\PhpCode::$functions = [
            '__' => '__',
            '_' => '__',
        ];

        $base = base_path();
        $entries = new Translations();

        foreach (self::$config['directories'] as $dir) {
            $dir = $base.'/'.$dir;

            if (!is_dir($dir)) {
                throw new Exception(__('Folder %s not exists. Gettext scan aborted.', $dir));
            }

            foreach (self::scanDir($dir) as $file) {
                if (strstr($file, '.blade.php')) {
                    $entries->mergeWith(Extractors\Blade::fromFile($file));
                } elseif (strstr($file, '.php')) {
                    $entries->mergeWith(Extractors\PhpCode::fromFile($file));
                }
            }
        }

        return $entries;
    }

    private static function scanDir($dir)
    {
        $directory = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::LEAVES_ONLY);

        $files = [];

        foreach ($iterator as $fileinfo) {
            $name = $fileinfo->getPathname();

            if (!strpos($name, '/.')) {
                $files[] = $name;
            }
        }

        return $files;
    }

    public static function getEntries($locale, $refresh = true)
    {
        if (empty($refresh) && ($cache = self::getCache($locale))) {
            return $cache;
        }

        $entries = clone self::scan();

        if (is_file($file = self::getFile($locale).'mo')) {
            $entries->mergeWith(Extractors\Mo::fromFile($file));
        }

        self::store($locale, $entries);

        return $entries;
    }

    public static function setEntries($locale, $translations)
    {
        if (empty($translations)) {
            return true;
        }

        $entries = self::getCache($locale) ?: (new Translations());

        foreach ($translations as $msgid => $msgstr) {
            $msgid = urldecode($msgid);

            if (!($entry = $entries->find(null, $msgid))) {
                $entry = $entries->insert(null, $msgid);
            }

            $entry->setTranslation($msgstr);
        }

        self::store($locale, $entries);

        return true;
    }

    public static function load()
    {
        $locale = self::$locale.'.UTF-8';

        # IMPORTANT: locale must be installed in server!
        # sudo locale-gen es_ES.UTF-8
        # sudo update-locale

        putenv('LC_ALL='.$locale);
        setlocale(LC_ALL, $locale);

        bindtextdomain(self::$config['domain'], self::$config['storage']);
        bind_textdomain_codeset(self::$config['domain'], 'UTF-8');
        textdomain(self::$config['domain']);

        # Also, we will work with gettext/gettext library
        # because PHP gones crazy when mo files are updated
        $path = dirname(self::getFile(self::$locale));
        $file = $path.'/'.self::$config['domain'];

        if (is_file($file.'.php')) {
            $translations = $file.'.php';
        } elseif (is_file($file.'.mo')) {
            $translations = Translations::fromMoFile($file.'.mo');
        } elseif (is_file($file.'.po')) {
            $translations = Translations::fromPoFile($file.'.po');
        } else {
            $translations = new Translations();
        }

        Translator::initGettextFunctions((new Translator())->loadTranslations($translations));
    }

    public static function setLocale($current, $new)
    {
        if (empty($current) || !in_array($current, self::$config['locales'])) {
            $current = self::$config['locales'][0];
        }

        if ($new && ($new !== $current) && in_array($new, self::$config['locales'])) {
            $current = $new;
        }

        self::$locale = $current;
    }

    public static function getLocale()
    {
        return self::$locale;
    }
}
