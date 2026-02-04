<?php

namespace Okeonline\FilamentArchivable\Tests;

use Filament\Facades\Filament;
use Illuminate\Database\Schema\Blueprint;
use Okeonline\FilamentArchivable\Tests\TestModels\User;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Filament::setCurrentPanel(
            Filament::getPanel('test'),
        );

        $this->actingAs(User::create(['email' => 'test@test.com']));
    }

    protected function getPackageProviders($app)
    {
        return [
            \BladeUI\Heroicons\BladeHeroiconsServiceProvider::class,
            \BladeUI\Icons\BladeIconsServiceProvider::class,
            \Filament\Actions\ActionsServiceProvider::class,
            \Filament\FilamentServiceProvider::class,
            \Filament\Forms\FormsServiceProvider::class,
            \Filament\Infolists\InfolistsServiceProvider::class,
            \Filament\Notifications\NotificationsServiceProvider::class,
            \Filament\Schemas\SchemasServiceProvider::class,
            \Filament\Support\SupportServiceProvider::class,
            \Filament\Tables\TablesServiceProvider::class,
            \Filament\Widgets\WidgetsServiceProvider::class,
            \Livewire\LivewireServiceProvider::class,
            \Okeonline\FilamentArchivable\FilamentArchivableServiceProvider::class,
            \Okeonline\FilamentArchivable\Tests\PanelProvider::class,
            \RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('view.paths', [
            ...$app['config']->get('view.paths'),
            __DIR__.'/../resources/views',
        ]);

        $schema = $app['db']->connection()->getSchemaBuilder();

        $schema->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->timestamps();
        });

        $schema->create('with', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();
        });

        $schema->create('without', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamp('archived_at')->nullable(); // does have the column, but model does not have the trait
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
