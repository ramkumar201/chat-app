import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from '@inertiajs/vue3';
import Helper from '@/Helper/Helper'

export default {
    components: {
        AuthenticatedLayout,
        Head,
        Link,
    },
    props: {

    },

    mounted() {
        console.log('Helloo');
        Helper.PrivateRoomConnect(1)
    },
}
