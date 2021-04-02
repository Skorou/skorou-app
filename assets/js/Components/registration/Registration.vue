<template>
    <div class="registration-container">
        <img src="assets/images/losanges.svg" class="dimamond-graphics" alt="image graphique losanges"/>
        <img src="assets/images/losanges.svg" class="dimamond-graphics-bottom" alt="image graphique losanges"/>
        <img src="assets/images/soleil.svg" class="sun-graphics" alt="image graphique soleil"/>
        <img src="assets/images/soleil.svg" class="sun-graphics-bottom" alt="image graphique soleil"/>
        <RegistrationContent title="test"></RegistrationContent>
    </div>
</template>

<script>
    import RegistrationContent from "./RegistrationContent";
    export default {
        name: "Registration",
        components: {RegistrationContent},
    }
</script>

<style scoped>

</style>