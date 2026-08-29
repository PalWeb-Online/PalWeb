<script setup>
const props = defineProps({
    relatives: {
        type: Array,
        required: true,
    },
    glosses: {
        type: Array,
        default: () => [],
    },
    validationErrors: {
        type: Object,
        default: () => ({}),
    },
});

const emit = defineEmits([
    'add',
    'remove',
]);

const relativeTypeGroups = [
    {
        label: 'Term Relative',
        types: [
            {value: 'variant', label: 'variant'},
            {value: 'reference', label: 'reference'},
            {value: 'component', label: 'component'},
            {value: 'descendant', label: 'descendant'},
        ],
    },
    {
        label: 'Derivative',
        types: [
            {value: 'source', label: 'source'},
            {value: 'ap', label: 'ap'},
            {value: 'pp', label: 'pp'},
            {value: 'vn', label: 'vn'},
        ],
    },
    {
        label: 'Gloss Relative',
        types: [
            {value: 'synonym', label: 'synonym'},
            {value: 'antonym', label: 'antonym'},
            {value: 'isPatient', label: 'isPatient'},
            {value: 'noPatient', label: 'noPatient'},
            {value: 'hasObject', label: 'hasObject'},
        ],
    },
];

const derivativeRelativeTypes = ['ap', 'pp', 'vn'];
const valenceRelativeTypes = ['isPatient', 'noPatient', 'hasObject'];
const glossRelativeTypes = ['synonym', 'antonym', 'isPatient', 'noPatient', 'hasObject'];

const allRelativeTypes = () => relativeTypeGroups.flatMap(group => group.types.map(type => type.value));

const allowedRelativeTypes = (relative) => {
    const originalType = relative.original_type ?? relative.type;

    // if the relative has just been added
    if (!relative.pivot_id) {
        return allRelativeTypes();
    }

    // if the relative has been auto-added from a source & has no type selected yet
    if (!originalType && relative.reciprocal_type === 'source') {
        return derivativeRelativeTypes;
    }

    // if the relative has been auto-added from a valence & has no type selected yet
    if (!originalType && valenceRelativeTypes.includes(relative.reciprocal_type)) {
        return valenceRelativeTypes;
    }

    if (['variant', 'reference'].includes(originalType)) {
        return ['variant', 'reference'];
    }

    if (['component', 'descendant'].includes(originalType)) {
        return [originalType];
    }

    if (originalType === 'source') {
        return [originalType];
    }

    if (derivativeRelativeTypes.includes(originalType)) {
        return derivativeRelativeTypes;
    }

    if (valenceRelativeTypes.includes(originalType)) {
        return valenceRelativeTypes;
    }

    // is there a good reason why you shouldn't be able to switch between the four "free" types?
    // probably not, but since these are gloss vs. term relatives, it's a bit more organized this way
    if (['synonym', 'antonym'].includes(originalType)) {
        return ['synonym', 'antonym'];
    }

    return [originalType];
};

const relativeTypeGroupOptions = (relativeTypeGroup, relative) => relativeTypeGroup.types
    .filter(type => allowedRelativeTypes(relative).includes(type.value));

const isRelativeTypeLocked = (relative) => relative.pivot_id && allowedRelativeTypes(relative).length === 1;

const handleRelativeTypeChange = (relative) => {
    if (!allowedRelativeTypes(relative).includes(relative.type)) {
        relative.type = relative.original_type ?? '';
    }

    // even though it's deleted here, would it actually be deleted in the database?
    if (!glossRelativeTypes.includes(relative.type)) {
        delete relative.gloss_id;
        return;
    }

    relative.gloss_id ??= '';
};
</script>

<template>
    <div class="field-block">
        <div class="field-block-head" @click="emit('add')">
            <div>{{ $t('components.term.relatives.title') }}</div>
            <div class="field-item-add">+</div>
        </div>

        <div class="field-block-body" v-if="relatives.length > 0">
            <div class="field-set" v-for="(relative, index) in relatives" :key="index">
                <span
                    v-if="relative.reciprocal_id"
                    class="material-symbols-rounded"
                    style="position: absolute; inset: -0.8rem"
                >
                    link_2
                </span>

                <img
                    src="/img/trash.svg"
                    alt="Delete"
                    v-show="relatives.length > 0"
                    @click="emit('remove', index)"
                />

                <div class="field-item">
                    <input :placeholder="relative.slug" disabled />

                    <div v-if="validationErrors[`relatives.${index}.slug`]" class="field-error">
                        {{ validationErrors[`relatives.${index}.slug`] }}
                    </div>

                    <select
                        v-model="relative.type"
                        :disabled="isRelativeTypeLocked(relative)"
                        @change="handleRelativeTypeChange(relative)"
                    >
                        <optgroup
                            v-for="relativeTypeGroup in relativeTypeGroups"
                            :key="relativeTypeGroup.label"
                            v-show="relativeTypeGroupOptions(relativeTypeGroup, relative).length"
                            :label="relativeTypeGroup.label"
                        >
                            <option
                                v-for="type in relativeTypeGroupOptions(relativeTypeGroup, relative)"
                                :key="type.value"
                                :value="type.value"
                            >
                                {{ type.label }}
                            </option>
                        </optgroup>
                    </select>

                    <div v-if="validationErrors[`relatives.${index}.type`]" class="field-error">
                        {{ validationErrors[`relatives.${index}.type`] }}
                    </div>

                    <template v-if="glossRelativeTypes.includes(relative.type)">
                        <select v-model="relative.gloss_id">
                            <option value=""></option>
                            <option
                                v-for="gloss in glosses.filter(g => g.id)"
                                :key="gloss.id"
                                :value="gloss.id"
                            >
                                {{ gloss.gloss }}
                            </option>
                        </select>

                        <div v-if="validationErrors[`relatives.${index}.gloss_id`]" class="field-error">
                            {{ validationErrors[`relatives.${index}.gloss_id`] }}
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>
