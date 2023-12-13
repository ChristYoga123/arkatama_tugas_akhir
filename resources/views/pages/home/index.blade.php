@extends('layouts.welcome')

@section('content')
    <section id="hero" class="h-screen w-full flex flex-col justify-center items-center">
        <div class="px-10 lg:px-20 mt-32 md:mt-44">
            <h1 class="text-3xl lg:text-6xl font-bold text-center">Create and Manage your Links
            </h1>
        </div>

        <div class="mt-5 px-20">
            <p class="text-lg text-center">The Best and The Shortest Link Shortener for Your URL</p>
        </div>

        <div class="flex justify-center mt-5">
            <button class="btn btn-warning border border-yellow-600">Try Now!</button>
        </div>

        <div class="flex justify-center mt-10">
            <img src="https://www.landingkit.com/templates/saasfolio/tailwind/img/hero-device.png" alt=""
                width="600" class="hover:scale-110 duration-200">
        </div>
    </section>

    <section id="features"
        class="w-full h-screen flex flex-col lg:flex-row justify-around items-center bg-[#EBF8FF] mt-24">
        <div class="px-10 lg:px-20 mt-14 lg:mt-0">
            <div class="flex flex-col gap-5">
                <h1 class="text-3xl lg:text-6xl font-bold">Simple, Fast, and Memorizeable</h1>
                <p class="text-xl">Experience the pinnacle of convenience with our cutting-edge URL shortener service,
                    designed to encapsulate the essence of simplicity, speed, and memorability.</p>
            </div>
        </div>
        <img src="https://home.s.id/images/landing/landing-shorten-0.png" alt="" width="700"
            class="px-10 lg:px-16">
    </section>

    <section id="pricing" class="w-full flex flex-col justify-around items-center">
        <div class="px-10 lg:px-20 mt-32 md:mt-44">
            <h1 class="text-3xl lg:text-5xl font-bold text-center">How much is a ShortenURL worth to you?
            </h1>
        </div>

        <div class="px-20">
            <p class="text-center mt-5 lg:mt-0">Choose between this pricing block, or end the page with a CTA Block as <br> shown below. Choose either one.</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-3">
            <div class="card w-96 bg-base-100 shadow-xl">
                <div class="card-body items-center text-center">
                    <h2 class="card-title">User Starter</h2>
                    <div class="flex flex-col">
                        <p class="text-5xl font-semibold">$0</p>
                        <p class="text-gray-400 w-[250px] mx-auto">Billed per month</p>
                    </div>

                    <p class="my-5 text-gray-400 w-[250px] mx-auto">Explain biggest difference of this plan here</p>
                    <ul>
                        <li>
                            <p>~ Max 5 links/month</p>
                        </li>
                    </ul>
                    <div class="card-actions mt-5">
                        <button class="btn btn-primary">Buy Now</button>
                    </div>
                </div>
            </div>

            <div class="card w-96 bg-base-100 shadow-xl">
                <div class="card-body items-center text-center">
                    <h2 class="card-title">User Starter</h2>
                    <div class="flex flex-col">
                        <p class="text-5xl font-semibold">$10</p>
                        <p class="text-gray-400 w-[250px] mx-auto">Billed per month</p>
                    </div>

                    <p class="my-5 text-gray-400 w-[250px] mx-auto">Explain biggest difference of this plan here</p>
                    <ul>
                        <li>
                            <p>~ Max 20 links/day</p>
                        </li>
                    </ul>
                    <div class="card-actions mt-5">
                        <button class="btn btn-primary">Buy Now</button>
                    </div>
                </div>
            </div>

            <div class="card w-96 bg-base-100 shadow-xl">
                <div class="card-body items-center text-center">
                    <h2 class="card-title">User Starter</h2>
                    <div class="flex flex-col">
                        <p class="text-5xl font-semibold">$20</p>
                        <p class="text-gray-400 w-[250px] mx-auto">Billed per month</p>
                    </div>

                    <p class="my-5 text-gray-400 w-[250px] mx-auto">Explain biggest difference of this plan here</p>
                    <ul>
                        <li>
                            <p>~ Max 40 links/day</p>
                        </li>
                    </ul>
                    <div class="card-actions mt-5">
                        <button class="btn btn-primary">Buy Now</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
