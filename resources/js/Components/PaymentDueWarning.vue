<script>
import { defineComponent } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';
export default defineComponent({

    props: {
        data: {
            type: Object
        }
    },

    components: {
        Link
    }
});
</script>

<template>
    <div class="card mb-5 mb-xl-10">
        <div class="card-body">
            <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6">
                <span class="svg-icon svg-icon-2tx svg-icon-warning me-4">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                        <rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="currentColor" />
                        <rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="currentColor" />
                    </svg>
                </span>
                <div class="d-flex flex-stack flex-grow-1">
                    <div class="fw-semibold">
                        <h4 class="text-gray-900 fw-bold">We need your attention!</h4>
                        <div class="fs-6 text-gray-700">Your plan was exceeded. To start using tools, please
                            <Link :href="`/subscription/plan-update/${data?.subscription?.id}`" class="fw-bold">update
                            plan</Link>.
                        </div>
                    </div>
                </div>
            </div>
           
        </div>
    </div>
</template>
