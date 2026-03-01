<?php

use App\Http\Controllers\Web\Backend\CMS\HomePage\HomePageController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {
    //Route for HomePageController
    /* Route::controller(HomePageController::class)->group(function () {
        Route::get('/cms/home-page/banner-section', 'bannerSection')->name('cms.home-page.banner-section');
        Route::patch('/cms/home-page/banner-section', 'storeBannerSection')->name('cms.home-page.banner-section.update');

        Route::get('/cms/home-page/difference-section', 'differenceSection')->name('cms.home-page.difference-section');
        Route::patch('/cms/home-page/difference-section', 'storeDifferenceSection')->name('cms.home-page.difference-section.update');
        Route::patch('/cms/home-page/difference-section/one', 'storeDifferenceSectionItemOne')->name('cms.home-page.difference-section.item-one.update');
        Route::patch('/cms/home-page/difference-section/two', 'storeDifferenceSectionItemTwo')->name('cms.home-page.difference-section.item-two.update');


        Route::get('/cms/home-page/process-section', 'processSection')->name('cms.home-page.process-section');
        Route::patch('/cms/home-page/process-section', 'storeProcessSection')->name('cms.home-page.process-section.update');
        Route::patch('/cms/home-page/process-section/two', 'storeProcessSectionStepTwo')->name('cms.home-page.process-section-step-two.update');
        Route::patch('/cms/home-page/process-section/three', 'storeProcessSectionStepThree')->name('cms.home-page.process-section-step-three.update');
    }); */

    
});
