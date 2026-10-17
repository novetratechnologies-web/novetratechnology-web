<template>
    <div class="cd-sermon-list">
        <a v-for="sermon in sermons" :key="sermon.id" :href="url+'/admin/sermon/show/'+sermon.sermons_id" class="cd-sermon-row">
            <img :src="sermon.cover_image || url+'/images/sermon-placeholder.png'" alt="" @error="$event.target.src=url+'/images/igm-church-logo.svg'">
            <span><strong>{{ sermon.title || 'Sermon' }}</strong><small><i class="far fa-calendar"></i>{{ sermon.sermon_date }} <b>·</b> {{ sermon.fullname || 'IGM Church' }}</small></span>
            <i class="fas fa-arrow-up-right-from-square cd-sermon-arrow"></i>
        </a>
        <div v-if="sermons.length === 0" class="cd-component-empty"><span><i class="fas fa-book-open"></i></span><strong>No sermons published</strong><small>New messages will appear here.</small></div>
    </div>

</template>

<script>
    export default {
        props:['url'],
        data () {
            return {
                sermons:[],
                errors:[],
                success:null,
            }
        },
        methods:
        {
            getData()
            {
                axios.get('/admin/dashboard/sermon').then(response => {
                    this.sermons = response.data.data;
                });
            }
        },
        created()
        {
            this.getData();
        }
    }
</script>
