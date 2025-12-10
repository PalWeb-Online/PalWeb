<div class="inflection-chart-wrapper">
    <div class="inflection-chart">
        @isset($title)
            <div class="inflection-chart-title" style="grid-column: span 2">{{ $title }}</div>
        @endisset

        @if(isset($conj1S) or isset($conj1P))

            @isset($conj1S)
                <div class="inflection-chart-item">
                    <div>1S</div>
                    <div>
                        <div>{{ $conj1S }}</div>
                        <div>{{ $conj1Str }}</div>
                    </div>
                </div>
            @endisset
            @isset($conj1P)
                <div class="inflection-chart-item">
                    <div>1P</div>
                    <div>
                        <div>{{ $conj1P }}</div>
                        <div>{{ $conj1Ptr }}</div>
                    </div>
                </div>
            @endisset

        @endif

        @if(isset($conj2M) or isset($conj2F))

            @isset($conj2M)
                <div class="inflection-chart-item">
                    <div>2M</div>
                    <div>
                        <div>{{ $conj2M }}</div>
                        <div>{{ $conj2Mtr }}</div>
                    </div>
                </div>
            @endisset
            @isset($conj2F)
                <div class="inflection-chart-item">
                    <div>2F</div>
                    <div>
                        <div>{{ $conj2F }}</div>
                        <div>{{ $conj2Ftr }}</div>
                    </div>
                </div>
            @endisset

        @endif

        @isset($conj2P)

            <div class="inflection-chart-item" style="grid-column: span 2">
                <div>2P</div>
                <div>
                    <div>{{ $conj2P }}</div>
                    <div>{{ $conj2Ptr }}</div>
                </div>
            </div>

        @endisset

        @if(isset($conj3M) or isset($conj3F))

            @isset($conj3M)
                <div class="inflection-chart-item">
                    <div>3M</div>
                    <div>
                        <div>{{ $conj3M }}</div>
                        <div>{{ $conj3Mtr }}</div>
                    </div>
                </div>
            @endisset
            @isset($conj3F)
                <div class="inflection-chart-item">
                    <div>3F</div>
                    <div>
                        <div>{{ $conj3F }}</div>
                        <div>{{ $conj3Ftr }}</div>
                    </div>
                </div>
            @endisset

        @endif

        @isset($conj3P)

            <div class="inflection-chart-item" style="grid-column: span 2">
                <div>3P</div>
                <div>
                    <div>{{ $conj3P }}</div>
                    <div>{{ $conj3Ptr }}</div>
                </div>
            </div>

        @endisset

        @if(isset($conjM) or isset($conjF))

            @isset($conjM)
                <div class="inflection-chart-item">
                    <div>M</div>
                    <div>
                        <div>{{ $conjM }}</div>
                        <div>{{ $conjMtr }}</div>
                    </div>
                </div>
            @endisset
            @isset($conjF)
                <div class="inflection-chart-item">
                    <div>F</div>
                    <div>
                        <div>{{ $conjF }}</div>
                        <div>{{ $conjFtr }}</div>
                    </div>
                </div>
            @endisset

        @endif

        @isset($conjP)

            <div class="inflection-chart-item"
                 style="{{ isset($conjM) && isset($conjF) ? 'grid-column: span 2' : '' }}">
                <div>P</div>
                <div>
                    <div>{{ $conjP }}</div>
                    <div>{{ $conjPtr }}</div>
                </div>
            </div>

        @endisset

        @if(isset($grt) && isset($rsp))

            <div class="inflection-chart-item">
                <div>G</div>
                <div>
                    <div>{{ $grt }}</div>
                    <div>{{ $grttr }}</div>
                </div>
            </div>

            <div class="inflection-chart-item">
                <div>R</div>
                <div>
                    <div>{{ $rsp }}</div>
                    <div>{{ $rsptr }}</div>
                </div>
            </div>

        @endif
    </div>
</div>
