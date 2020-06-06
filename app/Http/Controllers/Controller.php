<?php

namespace App\Http\Controllers;

use App\Card;
use App\Http\Resources\Category;
use App\Subcategory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\File;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function main()
    {
        return;
        $files = $this->getImagesFromFolder();
        $categories = \App\Category::all();
        $subcategories = Subcategory::all();
        //dd([$categories, $subcategories]);
        $counter = 1;
        foreach ($files as $file)
        {
            $category =  $this->getCategoryFromPath($file->getRelativePath());
            $categoryName = $categories->get($category-1)['name'];

            $fileName = $file->getFilenameWithoutExtension();
            $fullFileName = $file->getRelativePathname();
            list (,$subcategory, $photoNumber) = explode('-', $fileName);
            $photoNumber = intval($photoNumber);
            $subcategoryName = $subcategories->get($this->getCategoryFromPath($subcategory)-1)['name'];
            echo $counter++ .'. mam category='.$category.' categoryName='.$categoryName
                .', subcategory='.$subcategory.', subcategoryName='.$subcategoryName
                .', filename='
                .$fullFileName.', photonumber='.$photoNumber.'<br>';
            $card = new Card();
            $card->name = $categoryName . " " . $subcategory . " " . $photoNumber;
            $card->category_id = $category;
            $card->subcategory_id = $subcategory;
            $card->short_description = "$categoryName $subcategory $photoNumber";
            $card->description = "$categoryName $subcategoryName photo: $photoNumber";
            $card->image = $fullFileName;
            $card->save();
        }
    }

    private function getImagesFromFolder()
    {
        $path = public_path('images');
        $files = File::allFiles($path);

        return $files;
    }

    private function getCategoryFromPath($path)
    {
        return intval($path);
    }

    private function addCard() {

    }
}
