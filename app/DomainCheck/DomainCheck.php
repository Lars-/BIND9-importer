<?php

namespace App\DomainCheck;

class DomainCheck {
    private static self $instance;
    private array $topLevelDomains;

    public function __construct() {
        $this->topLevelDomains = explode("\n", file_get_contents(__DIR__ . '/tlds.txt'));
    }

    public static function isDomain(string $domain): bool {
        $domain          = strtoupper($domain);
        $topLevelDomains = self::instance()->getTLDs();
        foreach ($topLevelDomains as $tld) {
            if (str_ends_with($domain, '.' . $tld)) {
                $domain = str_replace('.' . $tld, '', $domain);
                if ( ! str_contains($domain, '.')) {
                    return true;
                }

                return false;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    private function getTLDs(): array {
        return $this->topLevelDomains;
    }

    public static function instance(): self {
        if ( ! isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
