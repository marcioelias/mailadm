<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AliasAccessPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Truncating Alias Access Policy table...');
        $this->truncateAliasAccessPolicyTable();

        $accessPolicies = [
            [
                'description' => 'Acesso Público',
                'alias_access_policy' => 'public',
            ],
            [
                'description' => 'Usuários do mesmo domínio',
                'alias_access_policy' => 'domain',
            ],
            [
                'description' => 'Usuários do mesmo domínio incluindo seus sub-domínios',
                'alias_access_policy' => 'subdomain',
            ],
            [
                'description' => 'Membros',
                'alias_access_policy' => 'membersonly',
            ],
            [
                'description' => 'Moderadores',
                'alias_access_policy' => 'moderatorsonly',
            ],
            [
                'description' => 'Moderadores e Membros',
                'alias_access_policy' => 'membersandmoderatorsonly',
            ],
        ];

        foreach($accessPolicies as $accessPolicy) {
            $this->command->info("Creating '{$accessPolicy['alias_access_policy']}' Alias Access Policy...");
            \app\AliasAccessPolicy::create($accessPolicy);
        }

        $this->command->info('Alias Access Policies created successful');
    }


    /**
     * Truncates all the laratrust tables and the users table
     *
     * @return    void
     */
    public function truncateAliasAccessPolicyTable()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('alias_access_policies')->truncate();
        $aliasAccessPoliciesTable = (new \App\AliasAccessPolicy)->getTable();
        DB::statement("TRUNCATE TABLE {$aliasAccessPoliciesTable} CASCADE");
        Schema::enableForeignKeyConstraints();
    }
}
