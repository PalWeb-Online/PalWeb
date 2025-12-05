<script setup>
const props = defineProps({
    forms: {type: Object, required: true}
})
</script>
<template>
    <div class="inflection-carousel">
        <div class="carousel-item">
            <div class="inflection-chart-wrapper">
                <div class="inflection-chart">
                    <div class="inflection-chart-item">
                        <div>1S</div>
                        <div>
                            <div>{{ forms['1S'].ar }}</div>
                            <div>{{ forms['1S'].tr }}</div>
                        </div>
                    </div>
                    <div class="inflection-chart-item">
                        <div>1P</div>
                        <div>
                            <div>{{ forms['1P'].ar }}</div>
                            <div>{{ forms['1P'].tr }}</div>
                        </div>
                    </div>
                    <div class="inflection-chart-item">
                        <div>2M</div>
                        <div>
                            <div>{{ forms['2M'].ar }}</div>
                            <div>{{ forms['2M'].tr }}</div>
                        </div>
                    </div>
                    <div class="inflection-chart-item">
                        <div>2F</div>
                        <div>
                            <div>{{ forms['2F'].ar }}</div>
                            <div>{{ forms['2F'].tr }}</div>
                        </div>
                    </div>
                    <div class="inflection-chart-item" style="grid-column: span 2">
                        <div>2P</div>
                        <div>
                            <div>{{ forms['2P'].ar }}</div>
                            <div>{{ forms['2P'].tr }}</div>
                        </div>
                    </div>
                    <div class="inflection-chart-item">
                        <div>3M</div>
                        <div>
                            <div>{{ forms['3M'].ar }}</div>
                            <div>{{ forms['3M'].tr }}</div>
                        </div>
                    </div>
                    <div class="inflection-chart-item">
                        <div>3F</div>
                        <div>
                            <div>{{ forms['3F'].ar }}</div>
                            <div>{{ forms['3F'].tr }}</div>
                        </div>
                    </div>
                    <div class="inflection-chart-item" style="grid-column: span 2">
                        <div>3P</div>
                        <div>
                            <div>{{ forms['3P'].ar }}</div>
                            <div>{{ forms['3P'].tr }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
