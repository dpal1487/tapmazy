
<style scoped>
.success-page {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f5f5f5;
}

.success-container {
    text-align: center;
    padding: 20px;
    border-radius: 10px;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
</style>
<script>
import { defineComponent } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';

export default defineComponent({
    props: {
        customer: {
            type: Object,
        },
        subscription: {
            type: Object,
        },
        session: {
            type: Object,
        }
    },
    data() {
        return {
            showCountdown: true,
            countdown: 30, // Set the initial countdown time in seconds (1 minute)
        };
    },
    components: { Link },
    methods: {
        redirectToDashboard() {
            // Navigate to the dashboard route
            this.$inertia.visit('/dashboard'); // Adjust the route as needed
        },
        startCountdown() {
            // Update the countdown every second
            const countdownInterval = setInterval(() => {
                if (this.countdown > 0) {
                    this.countdown--;
                } else {
                    // Stop the countdown when it reaches 0 and redirect
                    clearInterval(countdownInterval);
                    this.redirectToDashboard();
                }
            }, 1000);
        },
        manuallyRedirect() {
            // Manually trigger the redirect
            this.redirectToDashboard();
        }
    },
    mounted() {
        this.startCountdown();
    }
})
</script>
<template>
    <div class="d-flex align-items-center h-100 " style="background-color:var(--kt-app-bg-color);">
        <div class="container w-600px">
            <div class="card pt-0">
                <div class="card-header  text-center">
                    <img src="http://dgtlmrktng.s3.amazonaws.com/go/emails/generic-email-template/tick.png" alt="GO"
                        width="50" class="my-5 mx-auto">
                    <!-- <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-light rounded fs-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-check-square text-success">
                                <polyline points="9 11 12 14 22 4"></polyline>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                            </svg>
                        </span>
                    </div> -->
                </div>
                <div class="card-body">
                    <div class="text-center my-4">
                        <h3 class="text-gray-700">Hi <span class="text-hover-primary">{{ customer?.name }}</span>
                        </h3>
                        <h1 class="text-success">Payment Successful!</h1>
                        <p class="text-gray-700 fw-bold fs-6">Thank you for your purchase. Your transaction was successful.
                        </p>
                    </div>
                    <div class="d-flex flex-column flex-md-row gap-md-5">
                        <div class="d-flex gap-2">
                            <p class="flex-shrink-0"><strong>Transaction ID : </strong></p>
                            <p class="flex-root"><span class="text-gray-600"> {{ subscription?.id }}</span></p>
                        </div>
                        <p><strong>Amount Paid : </strong><span class="text-gray-500"> {{ subscription?.plan?.amount }}</span>
                        </p>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button @click="redirectToDashboard" class="btn btn-primary btn-sm mb-5">Go to Dashboard</button>
                    <p v-if="showCountdown" class="text-gray-700">Auto-redirecting in {{ countdown }} seconds...</p>

                </div>
            </div>
        </div>
    </div>
</template>
