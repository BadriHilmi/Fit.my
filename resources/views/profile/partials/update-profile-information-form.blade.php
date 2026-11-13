<section
    x-data="{
        name: @js(old('name', $user->name)),
        email: @js(old('email', $user->email)),
        age: @js(old('age', $user->age)),
        gender: @js(old('gender', $user->gender ?? 'male')),
        weight: @js(old('weight', $user->weight)),
        height: @js(old('height', $user->height)),
        activityLevel: @js(old('activity_level', '1.55')),
        goalMode: @js(old('goal_mode', 'custom')),
        calorieIntake: @js((int) old('calorie_intake', $user->calorie_intake ?? 2000)),

        avatarUrl: @js($user->image ? asset('images/' . $user->image) : null),
        removeImage: false,

        isSaving: false,
        toastMessage: '',
        toastError: false,
        toastTimer: null,

        get initials() {
            return (this.name || 'U').trim().substring(0, 2).toUpperCase();
        },
        get liveBmi() {
            const w = Number(this.weight);
            const h = Number(this.height);
            if (!w || !h || h <= 0) return null;
            return (w / Math.pow(h / 100, 2)).toFixed(1);
        },
        get liveBmiLabel() {
            const b = Number(this.liveBmi);
            if (!b) return 'Enter weight & height';
            if (b < 18.5) return 'Underweight';
            if (b < 25) return 'Healthy Weight';
            if (b < 30) return 'Overweight';
            return 'Obese';
        },
        get liveBmr() {
            const w = Number(this.weight);
            const h = Number(this.height);
            const a = Number(this.age);
            if (!w || !h || !a) return null;
            const bmr = this.gender === 'female'
                ? 447.593 + (9.247 * w) + (3.098 * h) - (4.330 * a)
                : 88.362 + (13.397 * w) + (4.799 * h) - (5.677 * a);
            return Math.round(bmr);
        },
        get liveRecommended() {
            if (!this.liveBmr) return null;
            return Math.round(this.liveBmr * Number(this.activityLevel || 1.55));
        },
        previewAvatar(event) {
            const file = event.target.files?.[0];
            if (!file) return;
            this.avatarUrl = URL.createObjectURL(file);
            this.removeImage = false;
        },
        clearAvatar() {
            this.avatarUrl = null;
            this.removeImage = true;
            if (this.$refs.imageInput) this.$refs.imageInput.value = '';
        },
        applyRecommended(offset = 0) {
            if (!this.liveRecommended) return;
            this.goalMode = 'custom';
            this.calorieIntake = Math.max(800, this.liveRecommended + offset);
        },
        showToast(message, isError = false) {
            this.toastMessage = message;
            this.toastError = isError;
            if (this.toastTimer) clearTimeout(this.toastTimer);
            this.toastTimer = setTimeout(() => {
                this.toastMessage = '';
            }, 2800);
        },
        async submitProfile(formEl) {
            this.isSaving = true;
            try {
                const data = await window.fitApi(formEl.action, {
                    method: 'POST',
                    body: new FormData(formEl),
                });

                if (data?.user) {
                    this.name = data.user.name;
                    this.email = data.user.email;
                    this.age = data.user.age;
                    this.gender = data.user.gender || this.gender;
                    this.weight = data.user.weight;
                    this.height = data.user.height;
                    this.calorieIntake = data.user.calorie_intake;
                    this.avatarUrl = data.user.image_url;
                    this.removeImage = false;
                }

                window.dispatchEvent(new CustomEvent('fit-profile-updated', { detail: data }));
                this.showToast(data?.message || 'Profile settings saved!');
            } catch (err) {
                this.showToast(err.message || 'Could not update profile.', true);
            } finally {
                this.isSaving = false;
            }
        }
    }"
>
    <!-- Live Save Toast -->
    <div
        x-show="toastMessage"
        x-transition
        x-cloak
        class="fixed bottom-6 right-6 z-50 flex items-center gap-2.5 rounded-2xl px-5 py-3.5 text-sm font-bold text-white shadow-2xl backdrop-blur-md"
        :class="toastError ? 'bg-rose-600' : 'bg-emerald-600'"
    >
        <template x-if="!toastError">
            <x-tabler-icon name="check" class="h-4 w-4" />
        </template>
        <template x-if="toastError">
            <x-tabler-icon name="alert-circle" class="h-4 w-4" />
        </template>
        <span x-text="toastMessage"></span>
    </div>

    <header class="border-b border-slate-200/70 pb-4 dark:border-slate-700">
        <h2 class="text-lg font-extrabold text-slate-900 dark:text-white">
            {{ __('Profile Information & Body Metrics') }}
        </h2>
        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
            {{ __("Update your account details, body measurements, and daily calorie goal.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form
        method="post"
        action="{{ route('profile.update') }}"
        enctype="multipart/form-data"
        @submit.prevent="submitProfile($el)"
        class="mt-6 space-y-6"
    >
        @csrf
        @method('patch')
        <input type="hidden" name="remove_image" :value="removeImage ? 1 : 0" />
        <input type="hidden" name="goal_mode" :value="goalMode" />

        <!-- Avatar Picker -->
        <div class="flex flex-col gap-4 rounded-2xl bg-slate-100/80 p-4 sm:flex-row sm:items-center sm:justify-between dark:bg-slate-900/50">
            <div class="flex items-center gap-4">
                <template x-if="avatarUrl">
                    <img :src="avatarUrl" alt="Profile picture" class="h-16 w-16 rounded-2xl object-cover shadow-md ring-2 ring-emerald-500/40" />
                </template>
                <template x-if="!avatarUrl">
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-600 text-xl font-black text-white shadow-md" x-text="initials">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                </template>
                <div>
                    <p class="text-sm font-bold text-slate-900 dark:text-white">Profile Photo</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">JPG, PNG, GIF or WEBP up to 2 MB.</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <label class="cursor-pointer rounded-xl bg-white px-3.5 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                    <span>Upload Photo</span>
                    <input
                        x-ref="imageInput"
                        id="image"
                        name="image"
                        type="file"
                        accept="image/*"
                        @change="previewAvatar($event)"
                        class="sr-only"
                    />
                </label>

                <button
                    type="button"
                    x-show="avatarUrl"
                    x-cloak
                    @click="clearAvatar()"
                    class="rounded-xl px-3 py-2 text-xs font-bold text-rose-600 transition hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-950/40"
                >
                    Remove
                </button>
            </div>
        </div>
        <x-input-error class="mt-1" :messages="$errors->get('image')" />

        <!-- Identity Fields -->
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <x-input-label for="name" :value="__('Full Name')" />
                <input
                    id="name"
                    name="name"
                    type="text"
                    x-model="name"
                    value="{{ old('name', $user->name) }}"
                    required
                    autocomplete="name"
                    class="mt-1.5 w-full rounded-xl border-slate-200 bg-white text-sm font-semibold focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                />
                <x-input-error class="mt-1.5" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email Address')" />
                <input
                    id="email"
                    name="email"
                    type="email"
                    x-model="email"
                    value="{{ old('email', $user->email) }}"
                    required
                    autocomplete="username"
                    class="mt-1.5 w-full rounded-xl border-slate-200 bg-white text-sm font-semibold focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                />
                <x-input-error class="mt-1.5" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-xs text-slate-600 dark:text-slate-400">
                            {{ __('Your email address is unverified.') }}
                            <button form="send-verification" class="font-bold text-emerald-600 underline hover:text-emerald-500">
                                {{ __('Re-send verification email.') }}
                            </button>
                        </p>
                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-1 text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Body Measurements -->
        <div>
            <div class="mb-2 flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                    Body Measurements
                </p>
                <span x-show="liveBmi" class="text-xs font-bold text-emerald-600 dark:text-emerald-400">
                    Live BMI: <span x-text="liveBmi"></span> (<span x-text="liveBmiLabel"></span>)
                </span>
            </div>

            <div class="grid gap-4 sm:grid-cols-4">
                <div>
                    <x-input-label for="age" :value="__('Age')" />
                    <input
                        id="age"
                        name="age"
                        type="number"
                        min="10"
                        max="120"
                        x-model.number="age"
                        value="{{ old('age', $user->age) }}"
                        class="mt-1.5 w-full rounded-xl border-slate-200 bg-white text-sm font-semibold focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                    />
                    <x-input-error class="mt-1.5" :messages="$errors->get('age')" />
                </div>

                <div>
                    <x-input-label for="gender" :value="__('Gender')" />
                    <select
                        id="gender"
                        name="gender"
                        x-model="gender"
                        class="mt-1.5 w-full rounded-xl border-slate-200 bg-white text-sm font-semibold focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                    >
                        <option value="male" @selected(old('gender', $user->gender) === 'male')>Male</option>
                        <option value="female" @selected(old('gender', $user->gender) === 'female')>Female</option>
                    </select>
                    <x-input-error class="mt-1.5" :messages="$errors->get('gender')" />
                </div>

                <div>
                    <x-input-label for="weight" :value="__('Weight (kg)')" />
                    <input
                        id="weight"
                        name="weight"
                        type="number"
                        min="20"
                        max="350"
                        x-model.number="weight"
                        value="{{ old('weight', $user->weight) }}"
                        class="mt-1.5 w-full rounded-xl border-slate-200 bg-white text-sm font-semibold focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                    />
                    <x-input-error class="mt-1.5" :messages="$errors->get('weight')" />
                </div>

                <div>
                    <x-input-label for="height" :value="__('Height (cm)')" />
                    <input
                        id="height"
                        name="height"
                        type="number"
                        min="80"
                        max="260"
                        x-model.number="height"
                        value="{{ old('height', $user->height) }}"
                        class="mt-1.5 w-full rounded-xl border-slate-200 bg-white text-sm font-semibold focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                    />
                    <x-input-error class="mt-1.5" :messages="$errors->get('height')" />
                </div>
            </div>
        </div>

        <!-- Daily Calorie Target & BMR Recalculation -->
        <div class="rounded-2xl bg-slate-100/80 p-4 space-y-4 dark:bg-slate-900/50">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300">
                        Daily Calorie Target
                    </p>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">
                        Set a custom kcal target or recalculate automatically from BMR &amp; activity level.
                    </p>
                </div>
                <div class="inline-flex rounded-xl border border-slate-200 bg-white p-1 dark:border-slate-700 dark:bg-slate-800">
                    <button
                        type="button"
                        @click="goalMode = 'custom'"
                        class="rounded-lg px-2.5 py-1 text-[11px] font-bold transition"
                        :class="goalMode === 'custom' ? 'bg-emerald-600 text-white' : 'text-slate-500 hover:text-slate-800 dark:text-slate-300'"
                    >
                        Custom
                    </button>
                    <button
                        type="button"
                        @click="goalMode = 'auto'"
                        class="rounded-lg px-2.5 py-1 text-[11px] font-bold transition"
                        :class="goalMode === 'auto' ? 'bg-emerald-600 text-white' : 'text-slate-500 hover:text-slate-800 dark:text-slate-300'"
                    >
                        Auto BMR
                    </button>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="activity_level" :value="__('Typical Activity Level')" />
                    <select
                        id="activity_level"
                        name="activity_level"
                        x-model="activityLevel"
                        class="mt-1.5 w-full rounded-xl border-slate-200 bg-white text-xs font-semibold focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                    >
                        @foreach ($activityLevels as $factor => $label)
                            <option value="{{ $factor }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div x-show="goalMode === 'custom'">
                    <x-input-label for="calorie_intake" :value="__('Daily Target (kcal)')" />
                    <input
                        id="calorie_intake"
                        name="calorie_intake"
                        type="number"
                        min="800"
                        max="10000"
                        x-model.number="calorieIntake"
                        value="{{ old('calorie_intake', (int) ($user->calorie_intake ?? 2000)) }}"
                        class="mt-1.5 w-full rounded-xl border-slate-200 bg-white text-sm font-black text-emerald-700 focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-emerald-400"
                    />
                    <x-input-error class="mt-1.5" :messages="$errors->get('calorie_intake')" />
                </div>

                <div x-show="goalMode === 'auto'" x-cloak class="rounded-xl bg-emerald-500/10 p-3 dark:bg-emerald-950/50">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-300">
                        Calculated Maintenance
                    </p>
                    <p class="mt-0.5 text-xl font-black text-emerald-700 dark:text-emerald-300">
                        <span x-text="liveRecommended ? liveRecommended.toLocaleString() : '—'"></span>
                        <span class="text-xs font-semibold">kcal/day</span>
                    </p>
                </div>
            </div>

            <!-- Quick Goal Preset Chips (based on live BMR recommendation) -->
            <div x-show="liveRecommended" class="flex flex-wrap items-center gap-2 pt-1">
                <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">Quick presets:</span>
                <button
                    type="button"
                    @click="applyRecommended(-300)"
                    class="rounded-lg bg-white px-2.5 py-1 text-[11px] font-bold text-slate-700 shadow-sm transition hover:bg-emerald-50 hover:text-emerald-700 dark:bg-slate-800 dark:text-slate-200"
                >
                    Fat Loss (-300: <span x-text="Math.max(800, liveRecommended - 300).toLocaleString()"></span>)
                </button>
                <button
                    type="button"
                    @click="applyRecommended(0)"
                    class="rounded-lg bg-white px-2.5 py-1 text-[11px] font-bold text-emerald-700 shadow-sm transition hover:bg-emerald-50 dark:bg-slate-800 dark:text-emerald-300"
                >
                    Maintain (<span x-text="liveRecommended.toLocaleString()"></span>)
                </button>
                <button
                    type="button"
                    @click="applyRecommended(300)"
                    class="rounded-lg bg-white px-2.5 py-1 text-[11px] font-bold text-slate-700 shadow-sm transition hover:bg-emerald-50 hover:text-emerald-700 dark:bg-slate-800 dark:text-slate-200"
                >
                    Lean Gain (+300: <span x-text="(liveRecommended + 300).toLocaleString()"></span>)
                </button>
            </div>
        </div>

        <div class="flex items-center justify-between pt-2">
            <div class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                @if (session('status') === 'profile-updated')
                    <span x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2500)" class="inline-flex items-center gap-1.5 font-bold">
                        <x-tabler-icon name="check" class="h-3.5 w-3.5" />
                        <span>Saved!</span>
                    </span>
                @endif
            </div>

            <button
                type="submit"
                :disabled="isSaving"
                class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-extrabold text-white shadow-sm transition hover:bg-emerald-500 disabled:opacity-60"
            >
                <span x-text="isSaving ? 'Saving...' : 'Save Profile Settings'">Save Profile Settings</span>
            </button>
        </div>
    </form>
</section>
