<section class="survey-personals">

    <div class="branding">

        <div class="img">
            <img src="{{ asset('/images/logo-footer.png') }}" alt="{{ Lang::get('master.hogent') }}"/>
        </div>

        <div class="title">{{ Lang::get("master.main-title") }}</div>

    </div>


    <div class="row">

        {{--instrument details--}}
        <div class="survey-details">

            <h3>{{ Lang::get('pdf.overzicht') }}</h3>

            <label class="control-label">{{ Lang::get('pdf.identifier') }}</label>
            <span>{{ $session->getIdentifier() }}</span>

            <label class="control-label">{{ Lang::get('pdf.created') }}</label>
            <span>{{ $session->created_at->format('d/m/Y') }}</span>

            <label class="control-label">{{ Lang::get('pdf.updated') }}</label>
            <span>{{ $session->updated_at->format('d/m/Y') }}</span>

            <label class="control-label">{{ Lang::get('pdf.answered') }}</label>
            <span>{{ $session->answers->filter(function($item){
                return $item->wasFilledIn();
            })->count()  }} / {{ $session->questionnaire->questions->count() }}</span>

        </div>


        {{-- hulpverlener details --}}
        @if($user = $session->user)

            <div class="survey-details">

                <h3>{{ Lang::get('pdf.hulpverlener') }}</h3>

                <label class="control-label">{{ Lang::get('pdf.fullname') }}</label>
                <span>{{ $user->fullname ? : ' ' }}</span>

                <label class="control-label">{{ Lang::get('pdf.organisation') }}</label>
                <span>{{ $user->organisation && $user->organisation->name ? $user->organisation->name : ' '}}</span>

                <label class="control-label">{{ Lang::get('pdf.location') }}</label>
                <span>{{ $user->organisation_location && $user->organisation_location->name ? $user->organisation_location->name : ' '}}</span>

            </div>

        @endif

    </div>

    <div class="row">

        {{--mantelzorger details--}}
        @if($mantelzorger = $session->mantelzorger)

            <div class="survey-details">

                <h3>{{ Lang::get('pdf.mantelzorger') }}</h3>

                <label class="control-label">{{ Lang::get('pdf.identifier') }}</label>
                <span>{{ $mantelzorger->identifier ? : ' '}}</span>

                <label class="control-label">{{ Lang::get('pdf.email') }}</label>
                <span>{{ $mantelzorger->email ? : ' ' }}</span>

                <label class="control-label">{{ Lang::get('pdf.fullname') }}</label>
                <span>{{ $mantelzorger->fullname ? : ' ' }}</span>

                <label class="control-label">{{ Lang::get('pdf.gender') }}</label>
                <span><i class="fa {{ $mantelzorger->male ? 'fa-male' : 'fa-female' }}"></i></span>

                <label class="control-label">{{ Lang::get('pdf.address') }}</label>
                <address>
                    {{ $mantelzorger->street  ? : ' '}}<br/>
                    {{ $mantelzorger->postal . ' ' . $mantelzorger->city }}
                </address>

                <label class="control-label">{{ Lang::get('pdf.birthday') }}</label>
                <span>{{ $mantelzorger->birthday ? $mantelzorger->birthday->format('d/m/Y') : ' ' }}</span>

                <label class="control-label">{{ Lang::get('pdf.phone') }}</label>
                <span>{{ $mantelzorger->phone ? : ' ' }}</span>

            </div>

        @endif

        {{--oudere details--}}
        @if($oudere = $session->oudere)

            <div class="survey-details">

                <h3>{{ Lang::get('pdf.hulpbehoevende') }}</h3>

                <label class="control-label">{{ Lang::get('pdf.identifier') }}</label>
                <span>{{ $oudere->identifier ? : ' ' }}</span>

                <label class="control-label">{{ Lang::get('pdf.fullname') }}</label>
                <span>{{ $oudere->fullname ? : ' ' }}</span>

                <label class="control-label">{{ Lang::get('pdf.gender') }}</label>
                <span><i class="fa {{ $oudere->male ? 'fa-male' : 'fa-female' }}"></i></span>

                <label class="control-label">{{ Lang::get('pdf.address') }}</label>
                <address>
                    {{ $oudere->street ? : ' ' }} <br/>
                    {{ $oudere->postal . ' ' . $oudere->city }}
                </address>

                <label class="control-label">{{ Lang::get('pdf.birthday') }}</label>
                <span>{{ $oudere->birthday ? $oudere->birthday->format('d/m/Y')  : ' '}}</span>

                <label class="control-label">{{ Lang::get('pdf.email') }}</label>
                <span>{{ $oudere->email ? : ' ' }}</span>

                <label class="control-label">{{ Lang::get('pdf.phone') }}</label>
                <span>{{ $oudere->phone ? : ' ' }}</span>

                <label class="control-label">{{ Lang::get('pdf.bel_profiel') }}</label>
                <span>{{ $oudere->belProfiel ? $oudere->belProfiel->value : ' ' }}</span>

                <label class="control-label">{{ Lang::get('pdf.woonsituatie') }}</label>
                <span>{{ $oudere->woonSituatie ? $oudere->woonSituatie->value : ' ' }}</span>

                <label class="control-label">{{ Lang::get('pdf.mantelzorger_relation') }}</label>
                <span>{{ $oudere->mantelzorgerRelation ? $oudere->mantelzorgerRelation->value : ' ' }}</span>

                <label class="control-label">{{ Lang::get('pdf.oorzaak_hulpbehoefte') }}</label>
                <span>{{ $oudere->oorzaakHulpbehoefte ? $oudere->oorzaakHulpbehoefte->value : ' ' }}</span>

                <label class="full">{{ Lang::get('pdf.diagnose') }}</label>
                <span>{{ $oudere->details_diagnose }}</span>


            </div>
        @endif

    </div>

</section>