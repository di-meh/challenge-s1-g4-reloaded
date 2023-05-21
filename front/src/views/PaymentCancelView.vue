<template>
    <div class="w-full m-8 flex flex-col gap-4">
        <h1 class="text-2xl font-bold">Votre paiement a été annulé</h1>
        <p class="text-lg">
            Vous pouvez retourner sur la page d'accueil en cliquant sur le
            bouton ci-dessous
        </p>
        <RouterLink
            to="/"
            class="w-32 rounded-md bg-indigo-600 py-2.5 px-3.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 text-center"
            ><button>Retourner à l'accueil</button></RouterLink
        >
    </div>
</template>
