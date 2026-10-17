<template>
    <div class="cd-vue-list">
        <template v-for="user in users">
            <div v-if="user !== 'no data'" :key="user.id || user.husbandUserName" class="cd-anniversary-couple">
                <div class="cd-anniversary-person"><img :src="user.husbandAvatar || url+'/uploads/user/avatar/default-user.jpg'" alt=""><a :href="url+'/admin/member/show/'+user.husbandUserName">{{ user.husbandName || 'Member' }}</a></div>
                <span class="cd-anniversary-heart"><i class="fas fa-heart"></i></span>
                <div class="cd-anniversary-person"><img :src="user.wifeAvatar || url+'/uploads/user/avatar/default-user.jpg'" alt=""><a :href="url+'/admin/member/show/'+user.wifeUserName">{{ user.wifeName || 'Member' }}</a></div>
            </div>
        </template>
        <div v-if="Object.keys(users).length === 0 || (users.length === 1 && users[0] === 'no data')" class="cd-component-empty"><span><i class="fas fa-heart"></i></span><strong>No anniversaries today</strong><small>Couple celebrations will appear here.</small></div>
        <a v-if="Object.keys(users).length && users[0] !== 'no data'" :href="url+'/admin/dashboard/anniversary'" class="cd-vue-action"><i class="fas fa-paper-plane"></i> Send anniversary wishes</a>
    </div>
</template>

<script>
    export default {
        props:['url'],
        data () {
            return {
                users: [],
                errors:[],
                success:null,
            }
        },

        methods:
        {
            getMember()
            {
                axios.get('/admin/dashboard/showAnniversary').then(response => {
                    this.users = response.data.data;
                });
            },
        },

        created()
        {
            this.getMember();
        }
    }
</script>
