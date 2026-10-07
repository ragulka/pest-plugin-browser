<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Pest\Browser\Support\Screenshot;

it('captures a full-page screenshot', function (): void {
    Route::get('/', fn (): string => 'Hello World');

    $page = visit('/');

    $page->screenshot(filename: 'full-page-screenshot.png');

    expect(file_exists(Screenshot::path('full-page-screenshot.png')))
        ->toBeTrue();
});

it('captures a full-page screenshot with default filename', function (): void {
    Route::get('/', fn (): string => 'Hello World');

    $page = visit('/');

    $page->screenshot();

    $defaultFilename = str_replace('__pest_evaluable_', '', test()->name());

    expect(file_exists(Screenshot::path($defaultFilename)))
        ->toBeTrue();
});

it('captures a screenshot with device scale', function (): void {
    Route::get('/', fn (): string => 'Hello World');

    $page = visit('/');

    $page->screenshot(fullPage: false, filename: 'device-scale-screenshot.png', scale: 'device');

    expect(getimagesize(Screenshot::path('device-scale-screenshot.png')))
        ->toMatchArray([0 => 3456, 1 => 2234]);
});

it('captures a screenshot in css pixels by default', function (): void {
    Route::get('/', fn (): string => 'Hello World');

    $page = visit('/');

    $page->screenshot(fullPage: false, filename: 'css-scale-screenshot.png');

    expect(getimagesize(Screenshot::path('css-scale-screenshot.png')))
        ->toMatchArray([0 => 1728, 1 => 1117]);
});

it('captures a screenshot of an specific element', function (): void {
    Route::get('/', fn (): string => '<div>
        <h1>Text outside of screenshot element</h1>
        <div id="screenshot-element">Text inside of screenshot element</div>
    </div>');

    $page = visit('/');

    $page->screenshotElement('#screenshot-element', 'element-screenshot.png');

    expect(file_exists(Screenshot::path('element-screenshot.png')))
        ->toBeTrue();
});

it('captures a screenshot of an element in device pixels by default', function (): void {
    Route::get('/', fn (): string => '<div id="box" style="width: 200px; height: 100px"></div>');

    $page = visit('/');

    $page->screenshotElement('#box', 'element-default-scale-screenshot.png');

    expect(getimagesize(Screenshot::path('element-default-scale-screenshot.png')))
        ->toMatchArray([0 => 400, 1 => 200]);
});

it('captures a screenshot of an element with css scale', function (): void {
    Route::get('/', fn (): string => '<div id="box" style="width: 200px; height: 100px"></div>');

    $page = visit('/');

    $page->screenshotElement('#box', 'element-css-scale-screenshot.png', scale: 'css');

    expect(getimagesize(Screenshot::path('element-css-scale-screenshot.png')))
        ->toMatchArray([0 => 200, 1 => 100]);
});
