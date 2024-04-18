<template>
  <jet-form-section @submitted="updateProfileInformation">
    <template #title>
      Profile Information
    </template>

    <template #description>
      Update your account's profile information and email address.
    </template>

    <template #form>
      <!-- Profile Photo -->
      <div class="mb-3" v-if="$page.props.jetstream.managesProfilePhotos">
        <!-- Profile Photo File Input -->
        <input type="file" hidden ref="photo" @change="updatePhotoPreview">

        <jet-label for="photo" value="Photo" />

        <!-- Current Profile Photo -->
        <div class="mt-2" v-show="!photoPreview">
          <img :src="`/storage/app/public/${user.profile_photo_path}`" alt="Current Profile Photo" class="rounded-circle">
        </div>
        <!-- New Profile Photo Preview -->
        <div class="mt-2" v-show="photoPreview">
          <img :src="photoPreview" class="rounded-circle" width="80px" height="80px">
        </div>

        <jet-secondary-button class="mt-2 me-2" type="button" @click.prevent="selectNewPhoto">
          Select A New Photo
        </jet-secondary-button>

        <jet-secondary-button type="button" class="mt-2" @click.prevent="deletePhoto" v-if="user.profile_photo_path">
          Remove Photo
        </jet-secondary-button>
        <jet-input-error :message="form.errors.photo" class="mt-2" />
      </div>

      <div class="w-75">
        <!-- Name -->
        <div class="row">
          <div class="col-6">
            <div class="mb-3">
              <jet-label for="first-name" value="First Name" />
              <jet-input id="first-name" type="text" v-model="form.first_name"
                :class="{ 'is-invalid': form.errors.first_name }" autocomplete="first_name" />
              <jet-input-error :message="form.errors.first_name" />
            </div>
          </div>
          <div class="col-6">
            <div class="mb-3">
              <jet-label for="last-name" value="Last Name" />
              <jet-input id="last-name" type="text" v-model="form.last_name"
                :class="{ 'is-invalid': form.errors.last_name }" autocomplete="last_name" />
              <jet-input-error :message="form.errors.last_name" />
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <div class="mb-3">
              <jet-label for="dob" value="Date Of Birth" />
              <VueDatePicker v-model="form.date_of_birth" :enable-time-picker="false" :clearable="false" auto-apply
                input-class-name="form-control form-control-lg form-control-solid fw-normal"
                :class="{ 'is-invalid': form.errors.date_of_birth }" placeholder=" Project start date"></VueDatePicker>
              <jet-input-error :message="form.errors.date_of_birth" />
            </div>
          </div>
          <div class="col-lg-6 fv-row">
            <jet-label for="dob" value="Gender" />
            <!--begin::Options-->
            <div class="d-flex align-items-center mt-3">
              <!--begin::Option-->
              <label class="form-check form-check-custom form-check-inline form-check-solid me-5">
                <input class="form-check-input" type="radio" value="male" name="gender" v-model="form.gender" />
                <div class="form-check-label">
                  Male
                </div>
              </label>
              <!--end::Option-->

              <!--begin::Option-->
              <label class="form-check form-check-custom form-check-inline form-check-solid">
                <input class="form-check-input" type="radio" value="female" name="gender" v-model="form.gender" />
                <div class="form-check-label">
                  Female
                </div>
              </label>
              <!--end::Option-->
            </div>
            <!--end::Options-->
            <div class="fv-plugins-message-container invalid-feedback"></div>
          </div>
        </div>

        <div class="row">
          <div class="col-6">
            <div class="mb-3">
              <jet-label for="email" value="Email" />
              <jet-input id="email" type="email" v-model="form.email" :class="{ 'is-invalid': form.errors.email }" />
              <jet-input-error :message="form.errors.email" />
            </div>
          </div>
          <div class="col-lg-6 fv-row">
            <jet-label for="dob" value="Theme Mode" />
            <!--begin::Options-->
            <div class="d-flex align-items-center mt-3">
              <!--begin::Option-->
              <label class="form-check form-check-custom form-check-inline form-check-solid me-5">
                <input class="form-check-input" type="radio" v-model="form.dark_mode" value="1" name="mode" />
                <div class="form-check-label">
                  Dark Mode
                </div>
              </label>
              <!--end::Option-->

              <!--begin::Option-->
              <label class="form-check form-check-custom form-check-inline form-check-solid">
                <input class="form-check-input" type="radio" v-model="form.dark_mode" value="0" name="mode" />
                <div class="form-check-label">
                  Light Mode
                </div>
              </label>
              <!--end::Option-->
            </div>

            <!--end::Options-->
            <div class="fv-plugins-message-container invalid-feedback"></div>



          </div>
        </div>
        <div class="row">
          <div class="col-lg-6 fv-row">
            <Multiselect id="project-status" :can-clear="false" :options="devices" label="label" valueProp="value"
              class="form-control form-control-solid" placeholder="Select status" mode="tags" :close-on-select="false"
              :create-option="true" :class="{ 'is-invalid': form.errors.role }" v-model="form.role" />
            <div v-for="(error, index) of form.role" :key="index">
              <input-error :message="error.$message" />
            </div>
          </div>
        </div>
      </div>
    </template>

    <template #actions>
      <jet-button :class="{ 'text-white-50': form.processing }" :disabled="form.processing">
        <div v-show="form.processing" class="spinner-border spinner-border-sm" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>

        Save
      </jet-button>
    </template>
  </jet-form-section>
</template>

<script>
import { defineComponent } from 'vue'
import JetButton from '@/Jetstream/Button.vue'
import JetFormSection from '@/Jetstream/FormSection.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetInputError from '@/Jetstream/InputError.vue'
import JetLabel from '@/Jetstream/Label.vue'
import JetActionMessage from '@/Jetstream/ActionMessage.vue'
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
import { toast } from "vue3-toastify";
import Multiselect from '@vueform/multiselect'
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

export default defineComponent({
  components: {
    JetActionMessage,
    JetButton,
    JetFormSection,
    JetInput,
    JetInputError,
    JetLabel,
    JetSecondaryButton,
    Multiselect,
    VueDatePicker,

  },

  props: ['user'],

  data() {
    return {
      form: this.$inertia.form({
        _method: 'PUT',
        first_name: this.user.first_name,
        last_name: this.user.last_name,
        date_of_birth: this.user.date_of_birth,
        gender: this.user.gender,
        dark_mode: this.user.dark_mode,
        email: this.user.email,
        photo: null,
      }),
      photoPreview: null,
    }
  },

  methods: {
    updateProfileInformation() {
      if (this.$refs.photo) {
        this.form.photo = this.$refs.photo.files[0]
      }

      this.form.post(route('user-profile-information.update'), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true, onSuccess: (data) => {
          toast.success("Profile updated successfully.");
        },
        onError: (data) => {
          console.log(data);
        },
      });
    },

    selectNewPhoto() {
      this.$refs.photo.click();
    },

    updatePhotoPreview() {
      const photo = this.$refs.photo.files[0];

      if (!photo) return;

      const reader = new FileReader();

      reader.onload = (e) => {
        this.photoPreview = e.target.result;
      };

      reader.readAsDataURL(photo);
    },

    deletePhoto() {
      this.$inertia.delete(route('current-user-photo.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
          this.photoPreview = null;
          this.clearPhotoFileInput();
        },
      });
    },

    clearPhotoFileInput() {
      if (this.$refs.photo?.value) {
        this.$refs.photo.value = null;
      }
    },
  },
})
</script>
