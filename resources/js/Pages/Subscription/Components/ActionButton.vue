<script>
import { defineComponent } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/css/index.css";
import axios from 'axios';
import { toast } from 'vue3-toastify';
export default defineComponent({
    props: {
        subscription_id: {
            type: String,
            default: null,
        },
        variation: {
            type: String,
            default: 'vertical',
        },
    },
    components: {
        Link,
        Loading
    },
    data() {
        return {
            isLoading: false,
            isFullPage: true,
        }
    },
    methods: {
        cancelSubscription(id) {
            this.isLoading = true;
            axios.post("/subscription/cancel", { subscription_id: id })
                .then((res) => {
                    if (res.data.status) {
                        console.log("Subscription success", res)
                        toast.success(res.data.message)
                    }
                    else {
                        toast.error(res.data.message)
                    }
                }).catch((error) => {
                    console.error("Error updating subscription:", error);
                    toast.error('Failed to update subscription. Please try again.'); // Display error toast
                })
                .finally(() => {
                    this.isLoading = false;
                    // Any cleanup code you want to execute
                });
        }
    }
});
</script>

<template>
    <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />

    <button :class="`btn btn-icon  btn-light btn-sm ${variation == 'horizontal' ? '' : 'btn-outline btn-circle'}`"
        :id="`dropdown-${subscription_id}`" data-bs-toggle="dropdown">
        <span v-if="variation == 'horizontal'" class="svg-icon svg-icon-3">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="10" y="10" width="4" height="4" rx="2" fill="currentColor" />
                <rect x="17" y="10" width="4" height="4" rx="2" fill="currentColor" />
                <rect x="3" y="10" width="4" height="4" rx="2" fill="currentColor" />
            </svg>
        </span>
        <i v-else class="bi bi-three-dots-vertical"></i>
    </button>
    <div class="text-left dropdown-menu-end mt-2 dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-6 w-200px py-4"
        :aria-labelled:by="`dropdown-${subscription_id}`">
        <div class="menu-item px-3">
            <Link :href="`/project/${subscription_id}/edit`" class="menu-link px-3">
            <span><i class="bi bi-pencil me-2"></i>Edit Subscription</span>
            </Link>
        </div>
        <div class="menu-item px-3">
            <span @click="cloneProject(subscription_id)" class="menu-link px-3"><i
                    class="bi bi-file-plus fs-5 me-2"></i>Pause Subscription</span>
        </div>
        <div class="menu-item px-3">
            <span @click="cancelSubscription(subscription_id)" class="menu-link text-danger px-3"><i
                    class="bi bi-trash3 me-2"></i> Cancel
                Subscription</span>
        </div>
    </div>
</template>
