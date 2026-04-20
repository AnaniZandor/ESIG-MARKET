<footer style="background: var(--bg-dark); color: rgba(255,255,255,0.8); margin-top: 64px;">

    {{-- CONTENU PRINCIPAL --}}
    <div class="container" style="padding: 48px 24px 32px;">

        <div style="display:grid; grid-template-columns: 2fr 1fr 1fr; gap:40px; margin-bottom:40px;">

            {{-- COLONNE 1 : LOGO + DESCRIPTION --}}
            <div>
                {{-- Logo + Nom --}}
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px;">
                    <img src="{{ asset('images/logo-esig.png') }}"
                         alt="ESIG"
                         style="height:40px; width:auto; object-fit:contain; ">
                    <span style="font-family:'Playfair Display',serif; font-size:20px; font-weight:700; color:white;">
                        Vintage<span style="color:var(--accent);">ESIG</span>
                    </span>
                </div>

                <p style="font-size:14px; line-height:1.7; color:rgba(255,255,255,0.6); max-width:300px;">
                    La marketplace officielle des étudiants de l'ESIG Global Success.
                    Achetez et vendez vos articles de seconde main en toute confiance.
                </p>

                {{-- Réseaux sociaux --}}
                <div style="display:flex; gap:10px; margin-top:20px;">
                    <a href="#" style="width:36px; height:36px; border-radius:50%; background:rgba(255,255,255,0.1); display:flex; align-items:center; justify-content:center; color:white; font-size:15px; transition:var(--transition);"
                       onmouseover="this.style.background='var(--primary)'"
                       onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" style="width:36px; height:36px; border-radius:50%; background:rgba(255,255,255,0.1); display:flex; align-items:center; justify-content:center; color:white; font-size:15px; transition:var(--transition);"
                       onmouseover="this.style.background='var(--primary)'"
                       onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>

            {{-- COLONNE 2 : LIENS RAPIDES --}}
            <div>
                <h4 style="font-size:13px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:white; margin-bottom:16px;">
                    Liens rapides
                </h4>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <a href="{{ route('articles.index') }}"
                       style="font-size:14px; color:rgba(255,255,255,0.6); transition:color var(--transition);"
                       onmouseover="this.style.color='var(--accent)'"
                       onmouseout="this.style.color='rgba(255,255,255,0.6)'">
                        <i class="fas fa-store" style="width:16px;"></i> Marketplace
                    </a>
                    @auth
                    <a href="{{ route('articles.create') }}"
                       style="font-size:14px; color:rgba(255,255,255,0.6); transition:color var(--transition);"
                       onmouseover="this.style.color='var(--accent)'"
                       onmouseout="this.style.color='rgba(255,255,255,0.6)'">
                        <i class="fas fa-plus" style="width:16px;"></i> Vendre un article
                    </a>
                    <a href="{{ route('favorites.index') }}"
                       style="font-size:14px; color:rgba(255,255,255,0.6); transition:color var(--transition);"
                       onmouseover="this.style.color='var(--accent)'"
                       onmouseout="this.style.color='rgba(255,255,255,0.6)'">
                        <i class="fas fa-heart" style="width:16px;"></i> Mes favoris
                    </a>
                    <a href="{{ route('profile.index') }}"
                       style="font-size:14px; color:rgba(255,255,255,0.6); transition:color var(--transition);"
                       onmouseover="this.style.color='var(--accent)'"
                       onmouseout="this.style.color='rgba(255,255,255,0.6)'">
                        <i class="fas fa-user" style="width:16px;"></i> Mon profil
                    </a>
                    @else
                    <a href="{{ route('register') }}"
                       style="font-size:14px; color:rgba(255,255,255,0.6); transition:color var(--transition);"
                       onmouseover="this.style.color='var(--accent)'"
                       onmouseout="this.style.color='rgba(255,255,255,0.6)'">
                        <i class="fas fa-user-plus" style="width:16px;"></i> Créer un compte
                    </a>
                    <a href="{{ route('login') }}"
                       style="font-size:14px; color:rgba(255,255,255,0.6); transition:color var(--transition);"
                       onmouseover="this.style.color='var(--accent)'"
                       onmouseout="this.style.color='rgba(255,255,255,0.6)'">
                        <i class="fas fa-right-to-bracket" style="width:16px;"></i> Se connecter
                    </a>
                    @endauth
                </div>
            </div>

            {{-- COLONNE 3 : CONTACTS ESIG --}}
            <div>
                <h4 style="font-size:13px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:white; margin-bottom:16px;">
                    Contact ESIG
                </h4>
                <div style="display:flex; flex-direction:column; gap:14px;">

                    {{-- Téléphone --}}
                    <a href="tel:+22893033351"
                       style="display:flex; align-items:flex-start; gap:10px; font-size:14px; color:rgba(255,255,255,0.6); text-decoration:none; transition:color var(--transition);"
                       onmouseover="this.style.color='var(--accent)'"
                       onmouseout="this.style.color='rgba(255,255,255,0.6)'">
                        <i class="fas fa-phone" style="margin-top:2px; color:var(--accent); flex-shrink:0;"></i>
                        <span>+228 93 03 33 51</span>
                    </a>

                    {{-- Email --}}
                    <a href="mailto:contact@esig.tg"
                       style="display:flex; align-items:flex-start; gap:10px; font-size:14px; color:rgba(255,255,255,0.6); text-decoration:none; transition:color var(--transition);"
                       onmouseover="this.style.color='var(--accent)'"
                       onmouseout="this.style.color='rgba(255,255,255,0.6)'">
                        <i class="fas fa-envelope" style="margin-top:2px; color:var(--accent); flex-shrink:0;"></i>
                        <span>contact@esig.tg</span>
                    </a>

                    {{-- Adresse --}}
                    <div style="display:flex; align-items:flex-start; gap:10px; font-size:14px; color:rgba(255,255,255,0.6);">
                        <i class="fas fa-location-dot" style="margin-top:2px; color:var(--accent); flex-shrink:0;"></i>
                        <span>Bvd de l'OTI, Bè-Kpota<br>face à l'église Catholique<br>Maria Goretti, Lomé - Togo</span>
                    </div>

                    {{-- Boîte postale --}}
                    <div style="display:flex; align-items:flex-start; gap:10px; font-size:14px; color:rgba(255,255,255,0.6);">
                        <i class="fas fa-mailbox" style="margin-top:2px; color:var(--accent); flex-shrink:0;"></i>
                        <span>11 BP : 149, Lomé - Togo</span>
                    </div>

                </div>
            </div>

        </div>

        {{-- SÉPARATEUR --}}
        <div style="height:1px; background:rgba(255,255,255,0.08); margin-bottom:24px;"></div>

        {{-- BAS DU FOOTER --}}
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
            <p style="font-size:13px; color:rgba(255,255,255,0.4);">
                © {{ date('Y') }} <strong style="color:rgba(255,255,255,0.7);">VintageESIG</strong>
                — Marketplace étudiante de l'ESIG Global Success
            </p>
            <p style="font-size:12px; color:rgba(255,255,255,0.3);">
                Développé par les étudiants en Génie Logiciel · ESIG {{ date('Y') }}
            </p>
        </div>

    </div>

</footer>