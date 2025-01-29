<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class MigrationController extends Controller
{
    public function runMigrationFresh()
    {
        try {
            // Run the migrations
            Artisan::call('migrate:fresh');

            // Optionally, you can get the output of the migration command
            $output = Artisan::output();

            return response()->json([
                'message' => 'Migrations fresh successfully executed', 
                'output' => $output
            ]);

        } 
        catch (\Exception $e) 
        {
            return response()->json([
                'error' => 'Error running migrations', 
                'message' => $e->getMessage()], 
                500
            );
        }
    }

    public function runMigrations()
    {
        try {
            // Run the migrations
            Artisan::call('migrate');

            // Optionally, you can get the output of the migration command
            $output = Artisan::output();

            return response()->json([
                'message' => 'Migrations successfully executed', 
                'output' => $output
            ]);

        } 
        catch (\Exception $e) 
        {
            return response()->json([
                'error' => 'Error running migrations', 
                'message' => $e->getMessage()], 
                500
            );
        }
    }

    public function runDbSeed()
    {
        try {
            // Run the migrations
            Artisan::call('db:seed');

            // Optionally, you can get the output of the migration command
            $output = Artisan::output();

            return response()->json([
                'message' => 'Migrations fresh successfully executed', 
                'output' => $output
            ]);

        } 
        catch (\Exception $e) 
        {
            return response()->json([
                'error' => 'Error running migrations', 
                'message' => $e->getMessage()], 
                500
            );
        }
    }



}