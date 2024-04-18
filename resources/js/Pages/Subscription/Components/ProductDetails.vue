<script>
import { defineComponent } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';
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
        }
    },
    components: {
        Link,
        ActionButton
    },
    data() {
        return {

        }
    },
});
</script>

<template >
    <div class="card p-0 mb-5">
        <div class="card-header">
            <div class="card-title">
                <h2 class="fw-bold">Product Details</h2>
            </div>
            <div class="card-toolbar">
                <a href="add.html" class="btn btn-light-primary">Update Product</a>
            </div>
        </div>
        <div class="card-body pt-3">
            <div class="mb-10">
                <h5 class="mb-4">Billing Address:</h5>
                <div class="d-flex flex-wrap py-5">
                    <div class="flex-equal me-5">
                        <table class="table fs-6 fw-semibold gs-0 gy-2 m-0">
                            <tr>
                                <td class="text-gray-700">Bill to:</td>
                                <td class="text-gray-500">{{
                                    customer?.email }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-gray-700">Customer Name:</td>
                                <td class="text-gray-500">
                                    {{ customer?.name }} </td>
                            </tr>
                            <tr>
                                <td class="text-gray-700">Address:</td>
                                <td class="text-gray-500">
                                    {{ customer?.address.line1 + " , " + customer?.address.line2 + " , " +
                                        customer?.address?.city + " , " + customer?.address?.state + " , " +
                                        customer?.address?.country + " , " + customer?.address?.postal_code }}.

                                </td>
                            </tr>
                            <tr>
                                <td class="text-gray-700">Phone:</td>
                                <td class="text-gray-500">{{ customer?.phone }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="flex-equal me-5">
                        <table class="table fs-6 fw-semibold gs-0 gy-2 m-0">
                            <tr>
                                <td class="text-gray-700">Subscribed Product:</td>
                                <td>
                                    <span class="badge badge-light-info fs-6">
                                        {{ product?.name }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-gray-700">Subscription Fees:</td>
                                <td class="text-gray-500">
                                    $ {{ subscription?.data?.plan?.plan_price }} /
                                    {{ subscription?.data?.plan?.plan_type }}

                                </td>
                            </tr>
                            <tr>
                                <td class="text-gray-700">Billing method:</td>
                                <td class="text-gray-500">
                                    {{ subscription?.data?.plan?.plan_type }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-gray-700">Currency:</td>
                                <td class="text-gray-500"> {{ subscription?.data?.currency?.currency_name }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mb-0">
                <h5 class="mb-4">Subscribed Products:</h5>
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6">
                        <thead>
                            <tr
                                class="border-bottom border-gray-200 text-start text-gray-700 fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-150px">Product</th>
                                <th class="min-w-125px">Subscription ID</th>
                                <th class="min-w-125px">Qty</th>
                                <th class="min-w-125px">Total</th>
                                <th class="text-end min-w-70px">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-500" v-for="(sub, index) in subscription.data?.plans"
                            :key="index">
                            <tr>
                                <td>
                                    <span class="fs-5 fw-bold text-capitalize">
                                        {{ sub?.plan_name }}
                                    </span>
                                </td>
                                <td><span class="badge badge-light-danger">{{ subscription?.data?.stripe_subscription_id
                                }}</span>
                                </td>
                                <td>{{ subscription?.data?.quantity }}</td>
                                <td>${{ sub?.plan_price }} / {{ sub?.plan_type }}</td>
                                <td class="text-end">
                                    <!-- {{ subscription.data.id }} -->
                                    <ActionButton :subscription_id="subscription?.data?.id" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
