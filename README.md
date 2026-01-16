# attendace-backend

🧩 Endpoints 

📌 LISTE DES ROUTES ABSENCES

1) GET /absences/
   → Absences : lister toutes les absences
```json
   [
    {
        "date_debut_absence": "2025-01-10",
        "date_fin_absence": "2025-01-10",
        "est_justifie": true,
        "etudiant": {
            "cin": "101010101",
            "email": "mamy@example.com",
            "id_etudiant": 1,
            "nom": "Rasolo",
            "num_matricule": "A001",
            "photo_reference": "/img/mamy.jpg",
            "prenom": "Mamy",
            "telephone": "0345600001"
        },
        "id_absence": 1,
        "id_etudiant": 1,
        "justification": "Maladie",
        "photo_justificatif": "/justifs/mamy.pdf",
        "remarque": "Certificat fourni"
    },
    {
        "date_debut_absence": "2025-01-12",
        "date_fin_absence": "2025-01-13",
        "est_justifie": false,
        "etudiant": {
            "cin": "202020202",
            "email": "fara@example.com",
            "id_etudiant": 2,
            "nom": "Rakoto",
            "num_matricule": "A002",
            "photo_reference": "/img/fara.jpg",
            "prenom": "Fara",
            "telephone": "0345600002"
        },
        "id_absence": 2,
        "id_etudiant": 2,
        "justification": "Famille",
        "photo_justificatif": null,
        "remarque": "Aucune pièce fournie"
    },
    {
        "date_debut_absence": "2025-01-15",
        "date_fin_absence": null,
        "est_justifie": false,
        "etudiant": {
            "cin": "303030303",
            "email": "tiana@example.com",
            "id_etudiant": 3,
            "nom": "Ranaivo",
            "num_matricule": "A003",
            "photo_reference": "/img/tiana.jpg",
            "prenom": "Tiana",
            "telephone": "0345600003"
        },
        "id_absence": 3,
        "id_etudiant": 3,
        "justification": "Retard",
        "photo_justificatif": null,
        "remarque": "Arrivé tard au cours"
    }
]
```

2) POST /absences/
   → Absences : ajouter une absence (avec ou sans justificatif)

3) GET /absences/etudiant/<id_etudiant>
   → Absences : lister les absences d’un étudiant
```json
[
    {
        "date_debut_absence": "2025-01-10",
        "date_fin_absence": "2025-01-10",
        "est_justifie": true,
        "etudiant": {
            "cin": "101010101",
            "email": "mamy@example.com",
            "id_etudiant": 1,
            "nom": "Rasolo",
            "num_matricule": "A001",
            "photo_reference": "/img/mamy.jpg",
            "prenom": "Mamy",
            "telephone": "0345600001"
        },
        "id_absence": 1,
        "id_etudiant": 1,
        "justification": "Maladie",
        "photo_justificatif": "/justifs/mamy.pdf",
        "remarque": "Certificat fourni"
    }
]
```

4) POST /absences/<id>/upload-justificatif
   → Absences : uploader / mettre à jour un justificatif

5) GET /absences/<id>/justificatif
   → Absences : télécharger le justificatif d'une absence

6) PUT /absences/<id>
   → Absences : modifier une absence

7) DELETE /absences/<id>
   → Absences : supprimer une absence (et son justificatif)

8) DELETE /absences/<id>/justificatif
   → Absences : supprimer uniquement le justificatif


📌 LISTE DES ROUTES CAMERAS

1) GET /cameras/
   → Caméras : lister toutes les caméras

2) GET /cameras/<id>
   → Caméras : obtenir une caméra par ID

3) GET /cameras/actives
   → Caméras : lister les caméras actives

4) POST /cameras/
   → Caméras : ajouter une caméra

5) PUT /cameras/<id>
   → Caméras : modifier une caméra

6) PATCH /cameras/<id>/toggle
   → Caméras : activer/désactiver une caméra

7) DELETE /cameras/<id>
   → Caméras : supprimer une caméra

8) GET /cameras/<id>/stats
   → Caméras : statistiques d'une caméra



📌 LISTE DES ROUTES ETUDIANTS

1) GET /etudiants/
   → Étudiants : lister tous les étudiants

   **Réponse :**
```json
   [
    {
        "cin": "101010101",
        "date_inscription": "2024-11-01",
        "date_naissance": "2002-05-12",
        "email": "mamy@example.com",
        "encodage_facial": "enc1",
        "id_etudiant": 1,
        "id_niveau": 1,
        "id_parcours": 1,
        "niveau": {
            "code_niveau": "L1",
            "id_niveau": 1
        },
        "nom": "Rasolo",
        "num_matricule": "A001",
        "parcours": {
            "code_parcours": "INF",
            "description": "Parcours de base en informatique",
            "id_parcours": 1,
            "libelle": "Informatique Générale"
        },
        "photo_reference": "/img/mamy.jpg",
        "prenom": "Mamy",
        "telephone": "0345600001"
    },
    {
        "cin": "202020202",
        "date_inscription": "2024-11-02",
        "date_naissance": "2001-09-22",
        "email": "fara@example.com",
        "encodage_facial": "enc2",
        "id_etudiant": 2,
        "id_niveau": 2,
        "id_parcours": 2,
        "niveau": {
            "code_niveau": "L2",
            "id_niveau": 2
        },
        "nom": "Rakoto",
        "num_matricule": "A002",
        "parcours": {
            "code_parcours": "RST",
            "description": "Formation en réseaux et télécommunications",
            "id_parcours": 2,
            "libelle": "Réseaux & Télécoms"
        },
        "photo_reference": "/img/fara.jpg",
        "prenom": "Fara",
        "telephone": "0345600002"
    },
    {
        "cin": "303030303",
        "date_inscription": "2024-11-03",
        "date_naissance": "2000-03-30",
        "email": "tiana@example.com",
        "encodage_facial": "enc3",
        "id_etudiant": 3,
        "id_niveau": 3,
        "id_parcours": 3,
        "niveau": {
            "code_niveau": "M1",
            "id_niveau": 3
        },
        "nom": "Ranaivo",
        "num_matricule": "A003",
        "parcours": {
            "code_parcours": "DS",
            "description": "Spécialisation en analyse de données",
            "id_parcours": 3,
            "libelle": "Data Science"
        },
        "photo_reference": "/img/tiana.jpg",
        "prenom": "Tiana",
        "telephone": "0345600003"
    }
]

```

2) POST /etudiants/
   → Étudiants : ajouter un étudiant (avec ou sans photo)

3) PUT /etudiants/<id>
   → Étudiants : modifier un étudiant

4) DELETE /etudiants/<id>
   → Étudiants : supprimer un étudiant

5) GET /etudiants/search?q=...
   → Étudiants : rechercher par nom, prénom ou matricule


📌 LISTE DES ROUTES NIVEAUX

1) GET /niveaux/
   → Niveaux : lister tous les niveaux
```json
[
    {
        "code_niveau": "L1",
        "description": "Licence 1 : Première année",
        "id_niveau": 1
    },
    {
        "code_niveau": "L2",
        "description": "Licence 2 : Deuxième année",
        "id_niveau": 2
    },
    {
        "code_niveau": "M1",
        "description": "Master 1 : Première année de Master",
        "id_niveau": 3
    }
]
```

2) POST /niveaux/
   → Niveaux : ajouter un niveau

3) PUT /niveaux/<id>
   → Niveaux : modifier un niveau

4) DELETE /niveaux/<id>
   → Niveaux : supprimer un niveau



📌 LISTE DES ROUTES PARCOURS

1) GET /parcours/
   → Parcours : lister tous les parcours

2) POST /parcours/
   → Parcours : ajouter un parcours

3) PUT /parcours/<id>
   → Parcours : modifier un parcours

4) DELETE /parcours/<id>
   → Parcours : supprimer un parcours


📌 LISTE DES ROUTES POINTAGES

1) GET /pointages/
   → Pointages : lister tous les pointages
```json
   [
    {
        "camera": {
            "adresse_ip": "192.168.1.11",
            "id_camera": 2,
            "nom_camera": "Cam-B2"
        },
        "confiance": 0.88,
        "date_pointage": "2025-01-20",
        "etudiant": {
            "cin": "202020202",
            "email": "fara@example.com",
            "id_etudiant": 2,
            "nom": "Rakoto",
            "num_matricule": "A002",
            "photo_reference": "/img/fara.jpg",
            "prenom": "Fara",
            "telephone": "0345600002"
        },
        "heure_arrivee": "07:10:00",
        "id_camera": 2,
        "id_etudiant": 2,
        "id_pointage": 2,
        "photo_capture": "/captures/fara_20012025.jpg"
    },
    {
        "camera": {
            "adresse_ip": "192.168.1.10",
            "id_camera": 1,
            "nom_camera": "Cam-A1"
        },
        "confiance": 0.95,
        "date_pointage": "2025-01-20",
        "etudiant": {
            "cin": "303030303",
            "email": "tiana@example.com",
            "id_etudiant": 3,
            "nom": "Ranaivo",
            "num_matricule": "A003",
            "photo_reference": "/img/tiana.jpg",
            "prenom": "Tiana",
            "telephone": "0345600003"
        },
        "heure_arrivee": "07:08:00",
        "id_camera": 1,
        "id_etudiant": 3,
        "id_pointage": 3,
        "photo_capture": "/captures/tiana_20012025.jpg"
    }
]
```

2) GET /pointages/<id>
   → Pointages : obtenir un pointage par ID

```json
{
    "camera": {
        "adresse_ip": "192.168.1.10",
        "id_camera": 1,
        "nom_camera": "Cam-A1"
    },
    "confiance": 0.92,
    "date_pointage": "2025-01-20",
    "etudiant": {
        "cin": "101010101",
        "email": "mamy@example.com",
        "id_etudiant": 1,
        "nom": "Rasolo",
        "num_matricule": "A001",
        "photo_reference": "/img/mamy.jpg",
        "prenom": "Mamy",
        "telephone": "0345600001"
    },
    "heure_arrivee": "07:05:00",
    "id_camera": 1,
    "id_etudiant": 1,
    "id_pointage": 1,
    "photo_capture": "/captures/mamy_20012025.jpg"
}
```

3) POST /pointages/
   → Pointages : ajouter un pointage (avec ou sans photo)

4) GET /pointages/etudiant/<id_etudiant>
   → Pointages : lister les pointages d’un étudiant

5) GET /pointages/camera/<id_camera>
   → Pointages : lister les pointages d’une caméra

6) GET /pointages/date/<date_pointage>
   → Pointages : lister les pointages par date

7) GET /pointages/periode?date_debut=YYYY-MM-DD&date_fin=YYYY-MM-DD
   → Pointages : lister les pointages par période

8) GET /pointages/stats
   → Pointages : statistiques des pointages

9) PUT /pointages/<id>
   → Pointages : modifier un pointage

10) DELETE /pointages/<id>
    → Pointages : supprimer un pointage
