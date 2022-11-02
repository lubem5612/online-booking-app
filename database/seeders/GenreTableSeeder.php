<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->genres as $genre) {
            $not_exist = Genre::where('name', $genre)->doesntExist();
            if ($not_exist) {
                Genre::create([
                    'name' => $genre
                ]);
            }
        }
    }

    protected $genres = ['Action', 'Comedy', 'Fantasy', 'Horror', 'Mystery', 'Drama', 'Fiction'];
}
