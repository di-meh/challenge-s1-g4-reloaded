<template>
    <!--
      This example requires updating your template:

      ```
      <html class="h-full">
      <body class="h-full">
      ```
    -->
    <main
        class="grid min-h-full place-items-center py-24 px-6 sm:py-32 lg:px-8"
    >
        <div class="text-center">
            <p class="text-base font-semibold text-indigo-600">404</p>
            <h1 class="mt-4 text-3xl font-bold tracking-tight sm:text-5xl">
                Oups, cette page n'existe pas.
            </h1>
            <div class="mt-10 flex items-center justify-center gap-x-6">
                <RouterLink
                    to="/"
                    class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                    >Revenir en lieu sûr</RouterLink
                >
            </div>
        </div>
    </main>
</template>
