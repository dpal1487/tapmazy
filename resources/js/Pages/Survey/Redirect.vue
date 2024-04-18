<script>
import { defineComponent } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";

export default defineComponent({
    props: ["data", 'redirect'],
    data() {
        return {
            data: this.data.data,
            redirect: this.redirect
        };
    },
    components: {
        Link,
        Head,
    },
    methods: {
        toRedirect() {
            setTimeout(() => {
                window.location.assign(this.redirect);
            }, 5000);
        }
    },
    mounted() {
        if (this.data.success) {
            this.toRedirect();
        }
    },
});
</script>
<style>
#circle {
    position: relative;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 200px;
    height: 200px;
}

.loader {
    width: calc(100% - 0px);
    height: calc(100% - 0px);
    border: 8px solid #0a72eb;
    border-top: 8px solid #fff;
    border-radius: 50%;
    animation: rotate 5s linear infinite;
}

@keyframes rotate {
    100% {
        transform: rotate(360deg);
    }
}
</style>
<template>
    <Head :title="data.headTitle" />
    <section class="vh-100">
        <div class="container-fluid h-100" style="background-image: url(/assets/images/redirect/bg.png);">
            <nav class="navbar navbar-expand-lg navbar px-10">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div>
                        <Link class="navbar-brand" href="index.html">Samplizy</Link>
                    </div>
                    <div class="navbar">
                        <ul class="navbar-nav ml-auto mr-md-3 text-white">
                            <li class="nav-item">
                                <Link href="/" class="nav-link text-white">Home</Link>
                            </li>
                            <li class="nav-item">
                                <Link href="#" class="nav-link text-white">Accessories</Link>
                            </li>
                            <li class="nav-item">
                                <Link href="#" class="nav-link text-white">Shop</Link>
                            </li>
                            <li class="nav-item">
                                <Link href="#" class="nav-link text-white">Products</Link>
                            </li>
                            <li class="nav-item">
                                <Link href="#" class="nav-link text-white">Contact</Link>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="row d-flex justify-content-around align-items-center h-100">
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.webp"
                        class="img-fluid" alt="Sample image">
                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                    <div class="text-white text-center">
                        <div id="circle">
                            <div class="loader">
                                <div class="loader">
                                    <div class="loader">
                                        <div class="loader">
                                            <div class="loader">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h2 class="mb-4 text-white" v-if="data.success">Redirecting...</h2>
                        <h1 class="mb-4 text-white">{{ data.title }}</h1>
                        <p class="mb-0 fs-4">{{ data.message }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>