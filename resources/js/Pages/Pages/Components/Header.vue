<script>
import { defineComponent } from 'vue';
import { Link } from "@inertiajs/inertia-vue3";
export default defineComponent({
    data() {
        return {
        }
    },
    components: {
        Link
    },
})
</script>
<template>
    <div class="card mb-12">
        <div class="card-body flex-column p-0">
            <div class="card-rounded bg-white d-flex flex-stack flex-wrap p-5">
                <ul class="nav flex-wrap border-transparent fw-bold gap-1">
                    <li class="nav-item">
                        <Link
                            :class="`btn btn-color-gray-600 btn-active-secondary btn-active-color-primary fw-bolder  nav-link text-uppercase ${route().current('page.terms-and-condition') && 'active'}`"
                            href="/page/terms-and-condition"> Terms and conditions
                        </Link>
                    </li>
                    <li class="nav-item">
                        <Link
                            :class="`btn btn-color-gray-600 btn-active-secondary btn-active-color-primary fw-bolder  nav-link  text-uppercase ${route().current('page.privacy-policy') && 'active'}`"
                            href="/page/privacy-policy">Privacy Policy</Link>
                    </li>
                    <li class="nav-item">
                        <Link
                            :class="`btn btn-color-gray-600 btn-active-secondary btn-active-color-primary fw-bolder  nav-link  text-uppercase ${route().current('page.refund-policy') && 'active'}`"
                            href="/page/refund-policy">Refund policy</Link>
                    </li>
                    <li class="nav-item">
                        <Link
                            :class="`btn btn-color-gray-600 btn-active-secondary btn-active-color-primary fw-bolder  nav-link  text-uppercase ${route().current('page.return-policy') && 'active'}`"
                            href="/page/return-policy">Return policy</Link>
                    </li>
                    <li class="nav-item">
                        <Link
                            :class="`btn btn-color-gray-600 btn-active-secondary btn-active-color-primary fw-bolder  nav-link  text-uppercase ${route().current('page.cancellation-policy') && 'active'}`"
                            href="/page/cancellation-policy">Cancellation policy</Link>
                    </li>
                    <li class="nav-item">
                        <Link
                            :class="`btn btn-color-gray-600 btn-active-secondary btn-active-color-primary fw-bolder  nav-link  text-uppercase ${route().current('page.about-us') && 'active'}`"
                            href="/page/about-us">About Us</Link>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
