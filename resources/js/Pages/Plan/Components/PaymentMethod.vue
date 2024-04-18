<script>
import { defineComponent, ref } from 'vue';
import { StripeCheckout } from '@vue-stripe/vue-stripe';

export default defineComponent({
    props: {
        plan: {
            plan: Object
        }
    },
    components: {
        StripeCheckout
    },
    data() {
        this.publishableKey = "pk_test_51MrLvHSG0vYz4HFAvSqOApta97IU3YLVO6GIRXs8KcidIlKaisPmt9njk0umDz2TtlHXUQ6ylT7hNilsdZL8Mr8f004YQGGVb3";
        return {
            loading: false,
            lineItems: [
                {
                    price: this.plan.stripe_plan, // The id of the one-time price you created in your Stripe dashboard
                    quantity: 1,
                },
            ],
            successURL: 'http://127.0.0.1:8000/checkout/success',
            cancelURL: 'http://127.0.0.1:8000/plan/' + this.plan?.id,
        };
    },
    mounted() {
        // console.log('http://127.0.0.1:8000/plan/' + this.plan?.id)
    },
    methods: {
        submit() {
            // You will be redirected to Stripe's secure checkout page
            this.$refs.checkoutRef.redirectToCheckout();
        },
    },
})
</script>
<template>
    <div class="card" data-kt-subscriptions-form="pricing">
        <div class="card-header">
            <div class="card-title">
                <h2 class="fw-bold">Payment Method</h2>
            </div>
            <div class="card-toolbar">
                <!-- <PaymentForm :show="isModalOpen" @hidemodal="hideCreateTicketModal" :plan="plan" /> -->
                <!-- <button type="submit" @click="showCreateTicketModal" class="btn btn-light-primary">New
                    Method</button> -->
                <stripe-checkout ref="checkoutRef" mode="payment" :pk="publishableKey" :line-items="lineItems"
                    :success-url="successURL" :cancel-url="cancelURL" @loading="v => loading = v" />
                <button @click="submit" class="btn btn-light-primary">Pay now!</button>
            </div>
        </div>
        <div class="card-body pt-0">
            <div id="kt_create_new_payment_method">
                <div class="py-1">
                    <div class="py-3 d-flex flex-stack flex-wrap">
                        <div class="d-flex align-items-center collapsible toggle" data-bs-toggle="collapse"
                            data-bs-target="#kt_create_new_payment_method_1">
                            <div class="btn btn-sm btn-icon btn-active-color-primary ms-n3 me-2">
                                <span class="svg-icon toggle-on svg-icon-primary svg-icon-2">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor" />
                                        <rect x="6.0104" y="10.9247" width="12" height="2" rx="1" fill="currentColor" />
                                    </svg>
                                </span>
                                <span class="svg-icon toggle-off svg-icon-2">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor" />
                                        <rect x="10.8891" y="17.8033" width="12" height="2" rx="1"
                                            transform="rotate(-90 10.8891 17.8033)" fill="currentColor" />
                                        <rect x="6.01041" y="10.9247" width="12" height="2" rx="1" fill="currentColor" />
                                    </svg>
                                </span>
                            </div>
                            <img src="/assets/media/svg/card-logos/mastercard.svg" class="w-40px me-3" alt="" />
                            <div class="me-3">
                                <div class="d-flex align-items-center fw-bold">Mastercard
                                    <div class="badge badge-light-primary ms-5">Primary</div>
                                </div>
                                <div class="text-muted">Expires Dec 2024</div>
                            </div>
                        </div>
                        <div class="d-flex my-3 ms-9">
                            <label class="form-check form-check-custom form-check-solid me-5">
                                <input class="form-check-input" type="radio" name="payment_method" checked="checked" />
                            </label>
                        </div>
                    </div>
                    <div id="kt_create_new_payment_method_1" class="collapse show fs-6 ps-10">
                        <div class="d-flex flex-wrap py-5">
                            <div class="flex-equal me-5">
                                <table class="table table-flush fw-semibold gy-1">
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">Name</td>
                                        <td class="text-gray-800">Emma Smith</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">Number</td>
                                        <td class="text-gray-800">**** 8514</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">Expires</td>
                                        <td class="text-gray-800">12/2024</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">Type</td>
                                        <td class="text-gray-800">Mastercard credit card</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">Issuer</td>
                                        <td class="text-gray-800">VICBANK</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">ID</td>
                                        <td class="text-gray-800">id_4325df90sdf8</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="flex-equal">
                                <table class="table table-flush fw-semibold gy-1">
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">Billing address
                                        </td>
                                        <td class="text-gray-800">AU</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">Phone</td>
                                        <td class="text-gray-800">No phone provided</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">Email</td>
                                        <td class="text-gray-800">
                                            <a href="#" class="text-gray-900 text-hover-primary">smith@kpmg.com</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">Origin</td>
                                        <td class="text-gray-800">Australia
                                            <div class="symbol symbol-20px symbol-circle ms-2">
                                                <img src="/assets/media/flags/australia.svg" />
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">CVC check</td>
                                        <td class="text-gray-800">Passed
                                            <span class="svg-icon svg-icon-2 svg-icon-success">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10"
                                                        fill="currentColor" />
                                                    <path
                                                        d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                        fill="currentColor" />
                                                </svg>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="separator separator-dashed"></div>
                <div class="py-1">
                    <div class="py-3 d-flex flex-stack flex-wrap">
                        <div class="d-flex align-items-center collapsible toggle collapsed" data-bs-toggle="collapse"
                            data-bs-target="#kt_create_new_payment_method_2">
                            <div class="btn btn-sm btn-icon btn-active-color-primary ms-n3 me-2">
                                <span class="svg-icon toggle-on svg-icon-primary svg-icon-2">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor" />
                                        <rect x="6.0104" y="10.9247" width="12" height="2" rx="1" fill="currentColor" />
                                    </svg>
                                </span>
                                <span class="svg-icon toggle-off svg-icon-2">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor" />
                                        <rect x="10.8891" y="17.8033" width="12" height="2" rx="1"
                                            transform="rotate(-90 10.8891 17.8033)" fill="currentColor" />
                                        <rect x="6.01041" y="10.9247" width="12" height="2" rx="1" fill="currentColor" />
                                    </svg>
                                </span>
                            </div>
                            <img src="/assets/media/svg/card-logos/visa.svg" class="w-40px me-3" alt="" />
                            <div class="me-3">
                                <div class="d-flex align-items-center fw-bold">Visa</div>
                                <div class="text-muted">Expires Feb 2022</div>
                            </div>
                        </div>
                        <div class="d-flex my-3 ms-9">
                            <label class="form-check form-check-custom form-check-solid me-5">
                                <input class="form-check-input" type="radio" name="payment_method" />
                            </label>
                        </div>
                    </div>
                    <div id="kt_create_new_payment_method_2" class="collapse fs-6 ps-10">
                        <div class="d-flex flex-wrap py-5">
                            <div class="flex-equal me-5">
                                <table class="table table-flush fw-semibold gy-1">
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">Name</td>
                                        <td class="text-gray-800">Melody Macy</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">Number</td>
                                        <td class="text-gray-800">**** 2977</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">Expires</td>
                                        <td class="text-gray-800">02/2022</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">Type</td>
                                        <td class="text-gray-800">Visa credit card</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">Issuer</td>
                                        <td class="text-gray-800">ENBANK</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">ID</td>
                                        <td class="text-gray-800">id_w2r84jdy723</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="flex-equal">
                                <table class="table table-flush fw-semibold gy-1">
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">Billing address
                                        </td>
                                        <td class="text-gray-800">UK</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">Phone</td>
                                        <td class="text-gray-800">No phone provided</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">Email</td>
                                        <td class="text-gray-800">
                                            <a href="#" class="text-gray-900 text-hover-primary">melody@altbox.com</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">Origin</td>
                                        <td class="text-gray-800">United Kingdom
                                            <div class="symbol symbol-20px symbol-circle ms-2">
                                                <img src="/assets/media/flags/united-kingdom.svg" />
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">CVC check</td>
                                        <td class="text-gray-800">Passed
                                            <span class="svg-icon svg-icon-2 svg-icon-success">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path opacity="0.3"
                                                        d="M10 18C9.7 18 9.5 17.9 9.3 17.7L2.3 10.7C1.9 10.3 1.9 9.7 2.3 9.3C2.7 8.9 3.29999 8.9 3.69999 9.3L10.7 16.3C11.1 16.7 11.1 17.3 10.7 17.7C10.5 17.9 10.3 18 10 18Z"
                                                        fill="currentColor" />
                                                    <path
                                                        d="M10 18C9.7 18 9.5 17.9 9.3 17.7C8.9 17.3 8.9 16.7 9.3 16.3L20.3 5.3C20.7 4.9 21.3 4.9 21.7 5.3C22.1 5.7 22.1 6.30002 21.7 6.70002L10.7 17.7C10.5 17.9 10.3 18 10 18Z"
                                                        fill="currentColor" />
                                                </svg>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="separator separator-dashed"></div>
                <div class="py-1">
                    <div class="py-3 d-flex flex-stack flex-wrap">
                        <div class="d-flex align-items-center collapsible toggle collapsed" data-bs-toggle="collapse"
                            data-bs-target="#kt_create_new_payment_method_3">
                            <div class="btn btn-sm btn-icon btn-active-color-primary ms-n3 me-2">
                                <span class="svg-icon toggle-on svg-icon-primary svg-icon-2">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor" />
                                        <rect x="6.0104" y="10.9247" width="12" height="2" rx="1" fill="currentColor" />
                                    </svg>
                                </span>
                                <span class="svg-icon toggle-off svg-icon-2">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor" />
                                        <rect x="10.8891" y="17.8033" width="12" height="2" rx="1"
                                            transform="rotate(-90 10.8891 17.8033)" fill="currentColor" />
                                        <rect x="6.01041" y="10.9247" width="12" height="2" rx="1" fill="currentColor" />
                                    </svg>
                                </span>
                            </div>
                            <img src="/assets/media/svg/card-logos/american-express.svg" class="w-40px me-3" alt="" />
                            <div class="me-3">
                                <div class="d-flex align-items-center fw-bold">American Express
                                    <div class="badge badge-light-danger ms-5">Expired</div>
                                </div>
                                <div class="text-muted">Expires Aug 2021</div>
                            </div>
                        </div>
                        <div class="d-flex my-3 ms-9">
                            <label class="form-check form-check-custom form-check-solid me-5">
                                <input class="form-check-input" type="radio" name="payment_method" />
                            </label>
                        </div>
                    </div>
                    <div id="kt_create_new_payment_method_3" class="collapse fs-6 ps-10">
                        <div class="d-flex flex-wrap py-5">
                            <div class="flex-equal me-5">
                                <table class="table table-flush fw-semibold gy-1">
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">Name</td>
                                        <td class="text-gray-800">Max Smith</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">Number</td>
                                        <td class="text-gray-800">**** 1173</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">Expires</td>
                                        <td class="text-gray-800">08/2021</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">Type</td>
                                        <td class="text-gray-800">American express credit card</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">Issuer</td>
                                        <td class="text-gray-800">USABANK</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">ID</td>
                                        <td class="text-gray-800">id_89457jcje63</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="flex-equal">
                                <table class="table table-flush fw-semibold gy-1">
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">Billing address
                                        </td>
                                        <td class="text-gray-800">US</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">Phone</td>
                                        <td class="text-gray-800">No phone provided</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">Email</td>
                                        <td class="text-gray-800">
                                            <a href="#" class="text-gray-900 text-hover-primary">max@kt.com</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">Origin</td>
                                        <td class="text-gray-800">United States of America
                                            <div class="symbol symbol-20px symbol-circle ms-2">
                                                <img src="/assets/media/flags/united-states.svg" />
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted min-w-125px w-125px">CVC check</td>
                                        <td class="text-gray-800">Failed
                                            <span class="svg-icon svg-icon-2 svg-icon-danger">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                                        transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                                    <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                                        transform="rotate(45 7.41422 6)" fill="currentColor" />
                                                </svg>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
