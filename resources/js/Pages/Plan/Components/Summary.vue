<script>
import { defineComponent } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';
import Show from '../Show.vue';
export default defineComponent({
    props: {
        plan: {
            plan: Object
        },
        user: {
            user: Object
        }

    },
    components: {
        Link,
        Show
    }

});
</script>

<template>
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h2>Summary</h2>
            </div>
        </div>
        <div class="card-body">
            <div class="">
                <h5 class="">Customer details</h5>
                <div class="d-flex align-items-center mb-1">
                    <span class="fw-semibold text-gray-600">Name : </span>
                    <Link :href="`account${user.id}`" class="fw-bold text-gray-800 text-hover-primary me-2">
                    {{ user.first_name + ' ' + user.last_name }}</Link>
                </div>
                <div class="d-flex align-items-center mb-1">
                    <span class="fw-semibold text-gray-600">Email : </span>
                    <span class="fw-bold text-gray-700">{{ user.email }}</span>
                </div>
            </div>
            <div class="separator separator-dashed my-7"></div>
            <div class="mb-7">
                <h5 class="mb-3">Product details</h5>
                <span class="fw-semibold text-gray-600">Plan Name : </span>
                <span class="badge badge-light-info me-2">{{ plan?.name }}</span>
                <div class="my-2">
                    <span class="fw-semibold text-gray-600">Plan Price : </span>
                    <span class="fw-semibold text-gray-600">{{ plan?.currency.symbols + '' + plan.price }} / Month</span>
                </div>
            </div>
            <div class="separator separator-dashed my-10"></div>
            <div class="text-center">
                <div class="mb-0">
                    <Show :plan="plan" />
                </div>
            </div>
        </div>
    </div>
</template>
