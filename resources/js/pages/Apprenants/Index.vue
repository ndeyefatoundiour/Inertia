<script setup lang="ts">
import EmptyLayout from '@/layouts/EmptyLayout.vue';
defineOptions({ layout: EmptyLayout });

import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Apprenant {
    id: number;
    nomComplet: string;
    sexe: 'M' | 'F';
    niveau: number;
    groupe_id: number | null;
    groupe?: { id: number; numero: number; niveau: number } | null;
}

interface Groupe {
    id: number;
    numero: number;
    niveau: number;
    apprenants: Apprenant[];
}

const props = defineProps<{
    apprenants: Apprenant[];
    groupes: Groupe[];
}>();

const formApprenant = useForm({
    nomComplet: '',
    sexe: 'M',
    niveau: 1,
});

const enregistrer = () => {
    formApprenant.post('/apprenants', {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => formApprenant.reset('nomComplet'),
    });
};

const formGroupes = useForm({ taille: 3 });

const genererGroupes = () => {
    formGroupes.post('/groupes/generer', {
        preserveScroll: true,
        preserveState: true,
    });
};

const totalGarcons = computed(() => props.apprenants.filter((a) => a.sexe === 'M').length);
const totalFilles = computed(() => props.apprenants.filter((a) => a.sexe === 'F').length);
</script>

<template>
    <div style="min-height: 100vh; background: #f8fafc; font-family: 'Inter', system-ui, -apple-system, sans-serif; color: #0f172a; padding-bottom: 5rem;">
        <div style="max-width: 960px; margin: 0 auto; padding: 3rem 1.5rem;">

            <!-- HEADER -->
            <header style="margin-bottom: 3rem;">
                <h1 style="color: #0f172a; font-size: 2rem; font-weight: 700; margin: 0; letter-spacing: -0.5px;">
                    Apprenants
                </h1>
                <p style="color: #64748b; font-size: 0.95rem; margin: 0.5rem 0 0 0;">
                    Gérez vos inscriptions et générez des groupes équilibrés
                </p>
            </header>

            <!-- STATS -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 0.75rem; margin-bottom: 2.5rem;">
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem;">
                    <div style="font-size: 0.7rem; font-weight: 600; letter-spacing: 0.5px; color: #64748b; text-transform: uppercase; margin-bottom: 0.5rem;">Total</div>
                    <div style="font-size: 1.75rem; font-weight: 700; color: #0f172a; line-height: 1;">{{ apprenants.length }}</div>
                </div>
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem;">
                    <div style="font-size: 0.7rem; font-weight: 600; letter-spacing: 0.5px; color: #64748b; text-transform: uppercase; margin-bottom: 0.5rem;">Garçons</div>
                    <div style="font-size: 1.75rem; font-weight: 700; color: #3b82f6; line-height: 1;">{{ totalGarcons }}</div>
                </div>
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem;">
                    <div style="font-size: 0.7rem; font-weight: 600; letter-spacing: 0.5px; color: #64748b; text-transform: uppercase; margin-bottom: 0.5rem;">Filles</div>
                    <div style="font-size: 1.75rem; font-weight: 700; color: #ec4899; line-height: 1;">{{ totalFilles }}</div>
                </div>
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem;">
                    <div style="font-size: 0.7rem; font-weight: 600; letter-spacing: 0.5px; color: #64748b; text-transform: uppercase; margin-bottom: 0.5rem;">Groupes</div>
                    <div style="font-size: 1.75rem; font-weight: 700; color: #10b981; line-height: 1;">{{ groupes.length }}</div>
                </div>
            </div>

            <!-- FORMULAIRE APPRENANT -->
            <section style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1.75rem; margin-bottom: 1.5rem;">
                <h2 style="color: #0f172a; font-size: 1.05rem; font-weight: 600; margin: 0 0 1.5rem 0;">
                    Enregistrer un apprenant
                </h2>

                <form @submit.prevent="enregistrer" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; align-items: end;">
                    <div>
                        <label style="display: block; color: #475569; font-size: 0.8rem; font-weight: 500; margin-bottom: 0.5rem;">
                            Nom complet
                        </label>
                        <input
                            v-model="formApprenant.nomComplet"
                            type="text"
                            placeholder="Awa Diop"
                            style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; color: #0f172a; background: #ffffff; outline: none; box-sizing: border-box; font-family: inherit;"
                        />
                        <p v-if="formApprenant.errors.nomComplet" style="color: #ef4444; font-size: 0.72rem; margin: 0.35rem 0 0 0;">{{ formApprenant.errors.nomComplet }}</p>
                    </div>

                    <div>
                        <label style="display: block; color: #475569; font-size: 0.8rem; font-weight: 500; margin-bottom: 0.5rem;">
                            Sexe
                        </label>
                        <select
                            v-model="formApprenant.sexe"
                            style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; color: #0f172a; background: #ffffff; outline: none; box-sizing: border-box; font-family: inherit; cursor: pointer;"
                        >
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>

                    <div>
                        <label style="display: block; color: #475569; font-size: 0.8rem; font-weight: 500; margin-bottom: 0.5rem;">
                            Niveau
                        </label>
                        <select
                            v-model.number="formApprenant.niveau"
                            style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; color: #0f172a; background: #ffffff; outline: none; box-sizing: border-box; font-family: inherit; cursor: pointer;"
                        >
                            <option :value="1">Niveau 1</option>
                            <option :value="2">Niveau 2</option>
                            <option :value="3">Niveau 3</option>
                        </select>
                    </div>

                    <div>
                        <button
                            type="submit"
                            :disabled="formApprenant.processing"
                            style="width: 100%; padding: 0.7rem 1rem; background: #0f172a; color: #ffffff; border: none; border-radius: 10px; font-size: 0.85rem; font-weight: 600; cursor: pointer; font-family: inherit;"
                        >
                            {{ formApprenant.processing ? 'Enregistrement...' : 'Enregistrer' }}
                        </button>
                    </div>
                </form>
            </section>

            <!-- GÉNÉRATION GROUPES -->
            <section style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1.75rem; margin-bottom: 2rem;">
                <h2 style="color: #0f172a; font-size: 1.05rem; font-weight: 600; margin: 0 0 0.35rem 0;">
                    Générer des groupes
                </h2>
                <p style="color: #64748b; font-size: 0.8rem; margin: 0 0 1.5rem 0;">
                    Même niveau par groupe · Mixité garçons/filles · Groupes numérotés
                </p>

                <form @submit.prevent="genererGroupes" style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: end;">
                    <div>
                        <label style="display: block; color: #475569; font-size: 0.8rem; font-weight: 500; margin-bottom: 0.5rem;">
                            Nombre par groupe
                        </label>
                        <input
                            v-model.number="formGroupes.taille"
                            type="number"
                            min="1"
                            style="width: 120px; padding: 0.65rem 0.85rem; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; color: #0f172a; background: #ffffff; outline: none; box-sizing: border-box; text-align: center;"
                        />
                        <p v-if="formGroupes.errors.taille" style="color: #ef4444; font-size: 0.72rem; margin: 0.35rem 0 0 0;">{{ formGroupes.errors.taille }}</p>
                    </div>

                    <button
                        type="submit"
                        :disabled="formGroupes.processing || apprenants.length === 0"
                        style="padding: 0.7rem 1.5rem; background: #10b981; color: #ffffff; border: none; border-radius: 10px; font-size: 0.85rem; font-weight: 600; cursor: pointer; font-family: inherit;"
                    >
                        {{ formGroupes.processing ? 'Génération...' : 'Générer les groupes' }}
                    </button>
                </form>
            </section>

            <!-- GROUPES -->
            <section v-if="groupes.length > 0" style="margin-bottom: 2rem;">
                <h2 style="color: #0f172a; font-size: 1.05rem; font-weight: 600; margin: 0 0 1rem 0;">
                    Groupes générés
                </h2>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1rem;">
                    <div
                        v-for="g in groupes"
                        :key="g.id"
                        style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem;"
                    >
                        <div style="margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid #f1f5f9;">
                            <div style="color: #0f172a; font-weight: 600; font-size: 0.9rem;">Groupe {{ g.numero }}</div>
                            <div style="color: #64748b; font-size: 0.72rem;">Niveau {{ g.niveau }} · {{ g.apprenants.length }} membre(s)</div>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                            <div
                                v-for="m in g.apprenants"
                                :key="m.id"
                                style="display: flex; align-items: center; gap: 0.6rem; font-size: 0.85rem;"
                            >
                                <div :style="{
                                    width: '24px', height: '24px', borderRadius: '50%',
                                    display: 'flex', alignItems: 'center', justifyContent: 'center',
                                    fontSize: '0.65rem', fontWeight: '600',
                                    background: m.sexe === 'M' ? '#dbeafe' : '#fce7f3',
                                    color: m.sexe === 'M' ? '#1e40af' : '#be185d',
                                    flexShrink: 0
                                }">
                                    {{ m.nomComplet.charAt(0).toUpperCase() }}
                                </div>
                                <span style="color: #334155; flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ m.nomComplet }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- LISTE APPRENANTS -->
            <section style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1.75rem;">
                <h2 style="color: #0f172a; font-size: 1.05rem; font-weight: 600; margin: 0 0 1.25rem 0;">
                    Liste des apprenants
                </h2>

                <div v-if="apprenants.length === 0" style="text-align: center; padding: 2.5rem 1rem; color: #94a3b8;">
                    <p style="margin: 0; font-size: 0.9rem;">Aucun apprenant enregistré pour le moment</p>
                </div>

                <div v-else style="display: flex; flex-direction: column;">
                    <div
                        v-for="a in apprenants"
                        :key="a.id"
                        style="display: flex; align-items: center; gap: 0.85rem; padding: 0.85rem 0; border-bottom: 1px solid #f1f5f9;"
                    >
                        <div :style="{
                            width: '36px', height: '36px', borderRadius: '50%',
                            display: 'flex', alignItems: 'center', justifyContent: 'center',
                            fontSize: '0.85rem', fontWeight: '600',
                            background: a.sexe === 'M' ? '#dbeafe' : '#fce7f3',
                            color: a.sexe === 'M' ? '#1e40af' : '#be185d',
                            flexShrink: 0
                        }">
                            {{ a.nomComplet.charAt(0).toUpperCase() }}
                        </div>

                        <div style="flex: 1; min-width: 0;">
                            <div style="color: #0f172a; font-weight: 500; font-size: 0.9rem;">{{ a.nomComplet }}</div>
                            <div style="color: #94a3b8; font-size: 0.75rem;">
                                Niveau {{ a.niveau }} · {{ a.sexe === 'M' ? 'Masculin' : 'Féminin' }}
                            </div>
                        </div>

                        <div v-if="a.groupe">
                            <span style="background: #ecfdf5; color: #047857; padding: 0.25rem 0.65rem; border-radius: 6px; font-size: 0.72rem; font-weight: 600;">
                                Groupe {{ a.groupe.numero }}
                            </span>
                        </div>
                        <div v-else style="color: #cbd5e1; font-size: 0.75rem;">
                            Non assigné
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
</template>