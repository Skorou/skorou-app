<template id="registration-content">
    <div class="registration-block">
        <p>{{title}}</p>

        <form
            @submit="submitRegistrationForm"
            method="post"
        >
            <label for="registration_form_email">
                Email
    <!--            <span class="invalid-feedback d-block">-->
    <!--                <span class="d-block">-->
    <!--                    <span class="form-error-icon badge badge-danger text-uppercase">Error</span>-->
    <!--                    <span class="form-error-message">There is already an account with this email</span>-->
    <!--                </span>-->
    <!--            </span>-->
            </label>
            <input type="email" name="registration_form[email]" id="registration_form_email" class="form-control" v-model="emailAddress" required autofocus>

            <label for="registration_form_username">Nom d'utilisateur</label>
            <input type="text" name="registration_form[username]" id="registration_form_username" class="form-control" v-model="username" required>

            <label for="registration_form_plainPassword">Password</label>
            <input type="password" name="registration_form[plainPassword]" id="registration_form_plainPassword" class="form-control" v-model="password" required>

            <input type="checkbox" id="registration_form_agreeTerms" name="registration_form[agreeTerms]" required="required" class="form-check-input is-invalid" value="1" v-model="termsAgreement">
            <label class="form-check-label required" for="registration_form_agreeTerms">J'accepte les conditions d'utilisation</label>

            <button class="btn btn-lg btn-primary" type="submit" id="registration_form_save" name="registration_form[save]">
                Sign up
            </button>
        </form>
    </div>
</template>

<script>
    export default {
        name: "RegistrationContent",
        props: ['title'],
        data() {
            return {
                errors: [],
                emailAddress: null,
                username: null,
                password: null,
                termsAgreement: null
            }
        },
        methods: {
            submitRegistrationForm: function (e) {
                this.errors = [];

                fetch('/register', {
                    headers: new Headers({
                        "X-Requested-With": "XMLHttpRequest"
                    }),
                    method: 'POST',
                    body: JSON.stringify({
                        'email': this.emailAddress,
                        'username': this.username,
                        'plainPassword': this.password,
                        'agreeTerms': this.termsAgreement
                    })
                }).then(function (response) {
                    return response.json().then(function(json) {
                        if(response.ok) {
                            console.log(json);
                        }
                        else
                        {
                            alert(error);
                        }
                    });
                }).catch(function (error) {
                    alert(error);
                });

                e.preventDefault();
            }
        }
    }
</script>

<style scoped>

</style>