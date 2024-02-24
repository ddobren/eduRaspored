<?php

// Registriran ili prijavljen korisnik

// Ruta za registraciju novog korisnika
Route::get("/register/{username:string}/{email:string}/{password:string}", "User/register@addNewUser")
    ->prefix("/api/v1");

// Ruta za prijavu korisnika
Route::get("/login/{email:string}/{password:string}", "User/login@loginUser")
    ->prefix("/api/v1");

// Ruta za odjavu korisnika
Route::options("/logout", "User/logout@logoutUser")
    ->prefix("/api/v1");

// Korisnik je prijavljen, sada može stvarati operacije

// Ruta za stvaranje novog rasporeda
Route::get("/new-schedule/{school_id:int}/{name:string}/{place:string}/{class_info:string}/{schedule:string}/{user_token:string}", "Schedule/schedule@addNewSchedule")
    ->prefix("/api/v1");

// Zadana ruta za pretraživanje rasporeda
Route::get("/search-schedule/{school_id:int}", "Schedule/searchSchedule@searchEvents")
    ->prefix("/api/v1");
