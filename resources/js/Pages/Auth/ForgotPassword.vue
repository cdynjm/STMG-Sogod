<script>
import { ref } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import Footer from '@/Layouts/Footer.vue';

export default {
    setup() {
        const form = useForm({
            email: '',
            error: null,
            success: null
        });

        const submit = () => {
            form.error = null;
            form.success = null;

            form.post(route('password.email'), {
                onSuccess: (page) => {
                    form.success = 'A reset link has been sent to your email address.';
                },
                onError: (errors) => {
                    form.error = errors.email;
                }
            });
        };

        return { form, submit };
    },
};
</script>

<template>
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
          <div class="container">
               <div class="row justify-content-center">
                    <div class="col-xl-5">
                         <div class="card auth-card shadow-lg">
                              <div class="card-body px-3 py-5">
                                   <div class=" mb-4 text-center auth-logo">
                                        <a href="#" class="logo-dark">
                                             <center>
                                                  <img src="/logo/stmg-2.png" class="me-2" style="width: 160px;" alt="logo dark">
                                                  <div class="fw-bold mt-2">STMG-Road Traffic Offense IMS</div>
                                             </center>
                                        </a>
                                   </div>

                                   <h2 class="fw-bold text-uppercase text-center fs-18">Forgot Password</h2>
                                   <p class="text-muted text-center mt-1 mb-4">Enter your email address to reset your password</p>

                                   <div class="px-4">


                                    <div class="alert alert-danger" v-if="form.error">
                                        {{ form.error }}
                                    </div>

                                    <div class="alert alert-success" v-if="form.success">
                                        {{ form.success }}
                                    </div>

                                    <form @submit.prevent="submit">
                                        <div>
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control form-control-sm mb-3" v-model="form.email"
                                                required autofocus />
                                        </div>
                                        <div>
                                            <button type="submit" class="btn btn-sm btn-primary">Send Password Reset
                                                Link</button>
                                        </div>
                                    </form>
                                        
                                   </div> <!-- end col -->
                              </div> <!-- end card-body -->
                         </div> <!-- end card -->
                    </div> <!-- end col -->
               </div> <!-- end row -->
          </div>
     </div>
</template>
