<?php

namespace Leapfrog;

class Bootstrap {
    public static function initialize() {
        Admin\Menu::register();
        CLI\Commands::register();
        Cron\Tasks::register();
    }
}
