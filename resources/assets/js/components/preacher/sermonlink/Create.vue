
<template>
  <div>
    <div v-if="success" class="alert alert-success" id="success-alert">{{ success }}</div>

    <div class="flex flex-row justify-between items-center mb-3">
      <div><h1 class="admin-h1">Sermon recordings and resources</h1><p class="text-sm text-gray-500">Add a YouTube recording, audio message, or downloadable study notes.</p></div>
      <button class="btn btn-success blue-bg text-white rounded-lg px-4 py-2 text-sm font-semibold"
              @click="openModal()">+ Add Chapter</button>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="modal-mask">
      <div class="modal-wrapper px-4">
        <div class="modal-container w-full max-w-lg px-8 mx-auto">

          <div class="modal-header flex justify-between items-center mb-4">
            <div><h2 class="text-lg font-bold">Add a sermon resource</h2><p class="mt-1 text-xs text-gray-500">A message can have video, audio, and a PDF outline.</p></div>
            <button class="text-2xl leading-none" @click="closeModal()">&times;</button>
          </div>

          <div class="modal-body">

            <!-- Title -->
            <div class="my-3 flex items-start gap-3">
              <label class="tw-form-label w-28 pt-1 shrink-0">Chapter Title <span class="text-red-500">*</span></label>
              <div class="flex-1">
                <input type="text" v-model="title" class="tw-form-control w-full" placeholder="e.g. Part 1 — Introduction">
                <p v-if="errors.title" class="text-red-500 text-xs mt-1">{{ errors.title[0] }}</p>
              </div>
            </div>

            <!-- Date -->
            <div class="my-3 flex items-start gap-3">
              <label class="tw-form-label w-28 pt-1 shrink-0">Publish Date <span class="text-red-500">*</span></label>
              <div class="flex-1">
                <input type="date" v-model="date" class="tw-form-control w-full">
                <p v-if="errors.date" class="text-red-500 text-xs mt-1">{{ errors.date[0] }}</p>
              </div>
            </div>

            <!-- Video Link -->
            <div class="my-3 flex items-start gap-3">
              <label class="tw-form-label w-28 pt-1 shrink-0">Video URL</label>
              <div class="flex-1">
                <input type="url" v-model="video_link" class="tw-form-control w-full" placeholder="https://youtube.com/...">
                <p class="mt-1 text-xs text-gray-500">Paste a YouTube or Vimeo watch link, or a direct MP4 URL.</p>
                <p v-if="errors.video_link" class="text-red-500 text-xs mt-1">{{ errors.video_link[0] }}</p>
              </div>
            </div>

            <!-- Audio Link -->
            <div class="my-3 flex items-start gap-3">
              <label class="tw-form-label w-28 pt-1 shrink-0">Audio URL</label>
              <div class="flex-1">
                <input type="url" v-model="audio_link" class="tw-form-control w-full" placeholder="https://soundcloud.com/...">
                <p class="mt-1 text-xs text-gray-500">Paste an audio file or hosted audio link.</p>
                <p v-if="errors.audio_link" class="text-red-500 text-xs mt-1">{{ errors.audio_link[0] }}</p>
              </div>
            </div>

            <!-- PDF Upload -->
            <div class="my-3 flex items-start gap-3">
              <label class="tw-form-label w-28 pt-1 shrink-0">Study file</label>
              <div class="flex-1">
                <input type="file" @change="onFileSelected" accept=".pdf,.doc,.docx" class="tw-form-control w-full">
                <p class="mt-1 text-xs text-gray-500">Attach sermon notes or a study outline (PDF or Word).</p>
                <p v-if="errors.pdf_link" class="text-red-500 text-xs mt-1">{{ errors.pdf_link[0] }}</p>
              </div>
            </div>

            <p v-if="mediaError" class="text-red-500 text-xs mt-1 mb-2">{{ mediaError }}</p>

            <div class="my-6">
              <button class="btn btn-primary blue-bg text-white rounded-lg px-5 py-2 text-sm font-semibold"
                      @click="sendData()">Submit</button>
            </div>

          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
export default {
  props: ['sermons_id', 'base_url'],

  data() {
    return {
      title: '',
      date: '',
      video_link: '',
      audio_link: '',
      pdf_file: null,
      showModal: false,
      errors: [],
      mediaError: null,
      success: null,
    };
  },

  methods: {
    openModal() {
      this.showModal = true;
    },

    closeModal() {
      this.showModal = false;
      this.errors = [];
      this.mediaError = null;
    },

    onFileSelected(event) {
      this.pdf_file = event.target.files[0] || null;
    },

    sendData() {
      this.errors = [];
      this.mediaError = null;
      this.success = null;

      if (!this.video_link && !this.audio_link && !this.pdf_file) {
        this.mediaError = 'Please provide at least one of Video URL, Audio URL, or PDF / Doc.';
        return;
      }

      let formData = new FormData();
      formData.append('title', this.title);
      formData.append('date', this.date);
      if (this.video_link) formData.append('video_link', this.video_link);
      if (this.audio_link) formData.append('audio_link', this.audio_link);
      if (this.pdf_file)   formData.append('pdf_link',   this.pdf_file);

      axios.post(
        (this.base_url || '/preacher') + '/links/' + this.sermons_id,
        formData,
        { headers: { 'Content-Type': 'multipart/form-data' } }
      ).then(response => {
        this.success = response.data.success;
        this.closeModal();
        window.location.reload();
      }).catch(error => {
        this.errors = error.response.data.errors;
      });
    },
  },
};
</script>

<style scoped>
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background-color: rgba(0, 0, 0, .5);
  display: table;
  transition: opacity .3s ease;
}
.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
  overflow: auto;
}
.modal-container {
  margin: 0 auto;
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 18px;
  box-shadow: 0 24px 80px rgba(3, 20, 36, .3);
  border: 1px solid #e5eaf0;
  height: auto;
  overflow: auto;
}
.modal-header { border-bottom: 1px solid #edf0f3; padding-bottom: 14px; color: #082846; }
.modal-header button { width: 34px; height: 34px; border-radius: 9px; color: #68798a; background: #f4f6f8; }
.modal-body .tw-form-label { font-size: 12px; font-weight: 700; color: #41566a; }
.modal-body .tw-form-control { border-color: #d9e0e6; border-radius: 9px; padding: 10px 12px; font-size: 13px; }
.modal-body .btn-primary { background: #082846; }
@media (max-width: 600px) { .modal-container { padding: 19px 17px; border-radius: 14px; max-height: 90vh; } .modal-body .my-3.flex { display: block; } .modal-body .tw-form-label { display:block; width:auto; margin-bottom:7px; } }
</style>
