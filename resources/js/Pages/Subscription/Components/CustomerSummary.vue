<script>
import { defineComponent } from 'vue';
import ActionButton from './ActionButton.vue';

export default defineComponent({
    props: {
        subscription: {
            type: Object,
            default: null,
        },
        customer: {
            type: Object,
            default: null
        },
        product: {
            type: Object,
            default: null
        },
        card_details: {
            type: Object,
        }
    },
    components: { ActionButton }
});
</script>

<template>
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h2>Summary</h2>
            </div>
            <div class="card-toolbar">
                <ActionButton :subscription_id="subscription?.data?.id" variation="horizontal" />
            </div>
        </div>
        <div class="card-body fs-6">
            <div class="mb-7">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-60px symbol-circle me-3">
                        <img alt="Pic" src="/assets/media/avatars/300-5.jpg" />
                    </div>
                    <div class="d-flex flex-column">
                        <a href="#" class="fs-4 fw-bold text-gray-900 text-hover-primary me-2">{{ subscription?.user_name
                        }}</a>
                        <a href="#" class="fw-semibold text-gray-600 text-hover-primary">{{ subscription?.email }}</a>
                    </div>
                </div>
            </div>
            <div class="separator separator-dashed mb-7"></div>
            <div class="mb-7">
                <h5 class="mb-4">Product details</h5>
                <div class="mb-0">

                    <span class="badge badge-light-info me-2">{{ subscription?.plan?.plan_name }}</span>
                    <span class="fw-semibold text-gray-600">${{ subscription?.plan?.plan_price }} / {{
                        subscription?.plan?.plan_type }}</span>
                </div>
            </div>
            <div class="separator separator-dashed mb-7"></div>
            <div class="mb-10">
                <h5 class="mb-4">Payment Details</h5>
                <div class="mb-0 ">
                    <div class="fw-semibold text-gray-600 d-flex align-items-center"
                        v-if="card_details?.brand == 'mastercard'">Mastercard
                        <img src="/assets/media/svg/card-logos/mastercard.svg" class="w-40px me-3" alt="">

                    </div>
                    <div class="fw-semibold text-gray-600 d-flex align-items-center" v-if="card_details?.brand == 'visa'">
                        Visa
                        <img src="/assets/media/svg/card-logos/visa.svg" class="w-35px ms-2" alt="" />
                    </div>
                    <div class="fw-semibold text-gray-600 d-flex align-items-center"
                        v-if="card_details?.brand == 'american-express'">American Express
                        <img src="/assets/media/svg/card-logos/american-express.svg" class="w-40px me-3" alt="">
                    </div>

                    <div class="fw-semibold text-gray-600"> Expires {{ card_details?.exp_month
                    }} / {{ card_details?.exp_year }}</div>
                </div>
            </div>
            <div class="separator separator-dashed mb-7"></div>
            <div class="mb-10">
                <h5 class="mb-4">Subscription Details</h5>
                <table class="table fs-6 fw-semibold gs-0 gy-2 gx-2">
                    <tr class="">
                        <td class="text-gray-400">Subscription ID:</td>
                        <td class="text-gray-800" style="word-break: break-all;">{{ subscription?.stripe_subscription_id }}
                        </td>
                    </tr>
                    <tr class="">
                        <td class="text-gray-400">Started:</td>
                        <td class="text-gray-800">{{ subscription?.current_plan_start }} </td>
                    </tr>
                    <tr class="">
                        <td class="text-gray-400">Status:</td>
                        <td>
                            <span :class="`badge badge-light-${subscription?.status == 'active' ? 'success' : 'danger'}`">{{
                                subscription?.status
                            }}</span>
                        </td>
                    </tr>
                    <tr class="">
                        <td class="text-gray-400">Next Invoice:</td>
                        <td class="text-gray-800">{{ subscription?.current_plan_end }}</td>
                    </tr>
                </table>
            </div>
            <div class="mb-0">
                <a href="add.html" class="btn btn-primary" id="kt_subscriptions_create_button">Edit Subscription</a>
            </div>
        </div>
    </div>
</template>
