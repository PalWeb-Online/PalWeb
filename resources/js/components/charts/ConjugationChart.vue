<script setup>
defineProps({
    conjugation: Object,
})
</script>

<template>
    <div class="inflection-chart-wrapper">
        <div class="inflection-chart">
            <div class="inflection-chart-title" style="grid-column: span 2">Present</div>
            <div class="inflection-chart-item">
                <div>1S</div>
                <div>
                    <div>{{ conjugation['imp1S'] }}</div>
                    <div>{{ conjugation['imp1Str'] }}</div>
                </div>
            </div>
            <div class="inflection-chart-item">
                <div>1P</div>
                <div>
                    <div>{{ conjugation['imp1P'] }}</div>
                    <div>{{ conjugation['imp1Ptr'] }}</div>
                </div>
            </div>
            <div class="inflection-chart-item">
                <div>2M</div>
                <div>
                    <div>{{ conjugation['imp3F'] }}</div>
                    <div>{{ conjugation['imp3Ftr'] }}</div>
                </div>
            </div>
            <div class="inflection-chart-item">
                <div>2F</div>
                <div>
                    <div>{{ conjugation['imp2F'] }}</div>
                    <div>{{ conjugation['imp2Ftr'] }}</div>
                </div>
            </div>
            <div class="inflection-chart-item" style="grid-column: span 2">
                <div>2P</div>
                <div>
                    <div>{{ conjugation['imp2P'] }}</div>
                    <div>{{ conjugation['imp2Ptr'] }}</div>
                </div>
            </div>
            <div class="inflection-chart-item">
                <div>3M</div>
                <div>
                    <div>{{ conjugation['imp3M'] }}</div>
                    <div>{{ conjugation['imp3Mtr'] }}</div>
                </div>
            </div>
            <div class="inflection-chart-item">
                <div>3F</div>
                <div>
                    <div>{{ conjugation['imp3F'] }}</div>
                    <div>{{ conjugation['imp3Ftr'] }}</div>
                </div>
            </div>
            <div class="inflection-chart-item" style="grid-column: span 2">
                <div>3P</div>
                <div>
                    <div>{{ conjugation['imp3P'] }}</div>
                    <div>{{ conjugation['imp3Ptr'] }}</div>
                </div>
            </div>
        </div>

        <div class="inflection-chart">
            <div class="inflection-chart-title" style="grid-column: span 2">Past</div>
            <div class="inflection-chart-item" style="grid-row: span 2">
                <div style="display: grid; gap: 1.6rem">
                    <div>1S</div>
                    <div>2M</div>
                </div>
                <div>
                    <div>{{ conjugation['past1S'] }}</div>
                    <div>{{ conjugation['past1Str'] }}</div>
                </div>
            </div>
            <div class="inflection-chart-item">
                <div>1P</div>
                <div>
                    <div>{{ conjugation['past1P'] }}</div>
                    <div>{{ conjugation['past1Ptr'] }}</div>
                </div>
            </div>
            <div class="inflection-chart-item">
                <div>2F</div>
                <div>
                    <div>{{ conjugation['past2F'] }}</div>
                    <div>{{ conjugation['past2Ftr'] }}</div>
                </div>
            </div>
            <div class="inflection-chart-item" style="grid-column: span 2">
                <div>2P</div>
                <div>
                    <div>{{ conjugation['past2P'] }}</div>
                    <div>{{ conjugation['past2Ptr'] }}</div>
                </div>
            </div>
            <div class="inflection-chart-item">
                <div>3M</div>
                <div>
                    <div>{{ conjugation['past3M'] }}</div>
                    <div>{{ conjugation['past3Mtr'] }}</div>
                </div>
            </div>
            <div class="inflection-chart-item">
                <div>3F</div>
                <div>
                    <div>{{ conjugation['past3F'] }}</div>
                    <div>{{ conjugation['past3Ftr'] }}</div>
                </div>
            </div>
            <div class="inflection-chart-item" style="grid-column: span 2">
                <div>3P</div>
                <div>
                    <div>{{ conjugation['past3P'] }}</div>
                    <div>{{ conjugation['past3Ptr'] }}</div>
                </div>
            </div>
        </div>

        <div class="inflection-chart">
            <div class="inflection-chart-title" style="grid-column: span 2">Command</div>
            <div class="inflection-chart-item">
                <div>2M</div>
                <div>
                    <div>{{ conjugation['amr2M'] }}</div>
                    <div>{{ conjugation['amr2Mtr'] }}</div>
                </div>
            </div>
            <div class="inflection-chart-item">
                <div>2F</div>
                <div>
                    <div>{{ conjugation['amr2F'] }}</div>
                    <div>{{ conjugation['amr2Ftr'] }}</div>
                </div>
            </div>
            <div class="inflection-chart-item" style="grid-column: span 2">
                <div>2P</div>
                <div>
                    <div>{{ conjugation['amr2P'] }}</div>
                    <div>{{ conjugation['amr2Ptr'] }}</div>
                </div>
            </div>
        </div>
    </div>
</template>
