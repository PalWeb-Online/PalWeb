<template>
  <!-- <teleport to="body">   -->
    <transition enter-from-class="opacity-0" leave-active-class="opacity-0">
      <div v-if="visibleState > 0" class="fixed z-50 inset-0 bg-gray-800 bg-opacity-50 overflow-x-hidden overflow-y-auto transition-opacity duration-150 ease-linear" @click.self="_closeOutsideClick">
        <transition appear enter-from-class="-translate-y-5" enter-active-class="transform" leave-active-class="transform" leave-to-class="-translate-y-5">
          <div v-if="visibleState > 1" class="mb-32 mt-8 mx-auto pointer-events-none transition-transform duration-150 ease-out" :style="{ maxWidth: maxWidth + 'px' }" :class="{ 'transform scale-105': bounce }">
            <div ref="modal" class="modal p-6 bg-white rounded-xl shadow-lg pointer-events-auto">
              <slot name="content" :close="close">
                <div v-if="!!title || closeIcon" class="flex justify-between">
                  <slot name="close" :close="close">
                    <h5 class="text-gray-800 text-xl font-extrabold">{{ title }}</h5>

                    <div class="flex items-center">
                      <slot name="header" />

                      <button v-if="closeIcon" type="button" aria-label="Close" class="ml-2 text-gray-600 hover:text-gray-900 rounded-md" @click.prevent="close">
                        <clear-icon class="w-5 h-5" />
                      </button>
                    </div>
                  </slot>
                </div>

                <div class="mt-4">
                  <h4 v-if="loading" class="flex items-center justify-center my-10"><loading-icon class="mr-2 w-6 h-6 text-teal-400" /> {{ __('Loading') }}...</h4>
                  <slot v-else :close="close" />
                </div>

                <div v-if="$slots.footer" class="mt-4">
                  <slot name="footer" :close="close" />
                </div>
              </slot>
            </div>
          </div>
        </transition>
      </div>
    </transition>
  <!-- </teleport> -->
</template>

<script>
    import { createFocusTrap } from 'focus-trap'
    import ClearIcon from './../Icons/ClearIcon.vue';
    import LoadingIcon from './../Icons/LoadingIcon.vue';

    let self
    export default {
        components: {
            ClearIcon,
            LoadingIcon,
        },
        props: {
            maxWidth: {
                type: Number,
                default: 600,
            },
            closeOutsideClick: {
                type: Boolean,
                default: false,
            },
            closeEsc: {
                type: Boolean,
                default: true,
            },
            closeIcon: {
                type: Boolean,
                default: true,
            },
            canClose: {
                type: Function,
                default: () => true,
            },
            title: String,
            loading: Boolean,
        },
        emits: ['closed', 'close'],
        data() {
            return {
                focusTrap: null,
                bounce: false,
                visibleState: 0,
            }
        },
        mounted() {
            self = this
            window.openModals = (window.openModals || 0) + 1
            document.body.style.overflow = 'hidden'
            document.addEventListener('keydown', this.escListener)

            this.$nextTick(() => {
                this.visibleState = 2
                this.waitForModalRef().then((modal) => {
                    this.focusTrap = createFocusTrap(modal, { allowOutsideClick: true }).activate()
                })
            })
        },
        beforeUnmount() {
            this.focusTrap?.deactivate()
            document.removeEventListener('keydown', this.escListener)
        },
        methods: {
            waitForModalRef() {
                return new Promise((resolve) => {
                    if (this.$refs.modal) {
                        resolve(this.$refs.modal)
                    } else {
                        setTimeout(() => this.waitForModalRef().then(resolve), 10)
                    }
                })
            },
            close(action, ...args) {
                if (this.canClose(action, ...args)) {
                    this.focusTrap?.deactivate()
                    this.visibleState = 1
                    this.$nextTick(() => {
                        this.visibleState = 0
                        this.$emit('close', action, ...args)
                        setTimeout(() => this.$emit('closed', action, ...args), 300)
                    })

                    window.openModals -= 1

                    if (window.openModals === 0) {
                        document.body.style.overflow = null
                    }

                    return true
                }

                self.animateBounce()

                return false
            },
            _closeOutsideClick() {
                this.closeOutsideClick ? this.close('click-outside') : this.animateBounce()
            },
            escListener(event) {
                if (event.key === 'Escape') {
                    self.closeEsc ? self.close('esc-key') : self.animateBounce()
                }
            },
            animateBounce() {
                this.bounce = true
                setTimeout(() => (this.bounce = false), 300)
            },
        },
    }
</script>
