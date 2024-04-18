<script>
import { defineComponent, ref } from 'vue';

export default defineComponent({
    props: {
        events: {
            type: Array,
            default: null
        }
    },
    setup(props) {
        const events = ref([]);


        // Function to format timestamp to a readable date format
        const formatDate = (timestamp) => {
            const date = new Date(timestamp * 1000);
            return date.toLocaleString(); // Adjust the format as needed
        };

        return {
            formatDate,
        };
    },
});
</script>
<template>
    <div class="card mb-5">
        <div class="card-header">
            <div class="card-title">
                <h2>Recent Events</h2>
            </div>
            <div class="card-toolbar">
                <a href="#" class="btn btn-light-primary">View All Events</a>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table table-row-dashed fs-6 text-gray-600 fw-semibold gy-5">
                    <tbody v-for="(event, index) in events" :key="index">
                        <tr>
                            <td class="min-w-400px">
                                {{ event.type }}
                                <!-- Invoice <a href="#"
                                    class="fw-bold text-gray-800 text-hover-primary me-1">5235-2968</a>
                                is <span class="badge badge-light-info">In Progress</span> -->
                            </td>
                            <td class="pe-0 text-gray-600 text-end min-w-200px">{{ formatDate(event.created) }} </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
