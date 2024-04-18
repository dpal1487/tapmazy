<script>
import { defineComponent } from 'vue';

export default defineComponent({

});
</script>

<template>
    <div class="card mb-5 mb-xl-10">
        <div class="card-body">
            <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-12 p-6">
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
                        <div class="fs-6 text-gray-700">Your payment was declined. To start using tools, please
                            <a href="#" class="fw-bold" data-bs-toggle="modal" data-bs-target="#kt_modal_new_card">Add
                                Payment Method</a>.
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-7">
                    <h3 class="mb-2">Active until Dec 09, 2022</h3>
                    <p class="fs-6 text-gray-600 fw-semibold mb-6 mb-lg-15">We will send you a notification upon
                        Subscription expiration</p>
                    <div class="fs-5 mb-2">
                        <span class="text-gray-800 fw-bold me-1">$24.99</span>
                        <span class="text-gray-600 fw-semibold">Per Month</span>
                    </div>
                    <div class="fs-6 text-gray-600 fw-semibold">Extended Pro Package. Up to 100 Agents & 25 Projects</div>
                </div>
                <div class="col-lg-5">
                    <div class="d-flex text-muted fw-bold fs-5 mb-3">
                        <span class="flex-grow-1 text-gray-800">Users</span>
                        <span class="text-gray-800">86 of 100 Used</span>
                    </div>
                    <div class="progress h-8px bg-light-primary mb-2">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 86%" aria-valuenow="86"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="fs-6 text-gray-600 fw-semibold mb-10">14 Users remaining until your plan requires update
                    </div>
                    <div class="d-flex justify-content-end pb-0 px-0">
                        <a href="#" class="btn btn-light btn-active-light-primary me-2"
                            id="kt_account_billing_cancel_subscription_btn">Cancel Subscription</a>
                        <button class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#kt_modal_upgrade_plan">Upgrade Plan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
