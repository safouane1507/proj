<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Resource;
use App\Models\UnavailabilityPeriod;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Création de l'Administrateur
        User::create([
            'name'      => 'Admin Principal',
            'email'     => 'admin@datacenter.com',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        User::create([
            'name'      => 'Safouane',
            'email'     => 'safouane@safouane.com',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        // 2. Création d'un Responsable Technique
        $manager = User::create([
            'name'      => 'Mohamed Reda Manager',
            'email'     => 'manager@datacenter.com',
            'password'  => Hash::make('password'),
            'role'      => 'manager',
            'is_active' => true,
        ]);

        // 3. Création d'un Utilisateur Standard
        User::create([
            'name'      => 'Mohamed Reda',
            'email'     => 'user@datacenter.com',
            'password'  => Hash::make('12345678'),
            'role'      => 'user',
            'is_active' => true,
        ]);

        User::create([
            'name'      => 'Mohamed Reda',
            'email'     => 'reda@reda.com',
            'password'  => Hash::make('password'),
            'role'      => 'user',
            'is_active' => true,
        ]);

        // 4. Création des Ressources

        // --- Serveurs Physiques ---
        $dell = Resource::create([
            'label'          => 'Serveur Dell PowerEdge R740',
            'category'       => 'Serveur Physique',
            'description'    => 'Serveur haute performance pour calcul intensif et bases de données critiques.',
            'location'       => 'Baie A - Rack 4',
            'status'         => 'available',
            'condition'      => 'bon',
            'internal_notes' => 'Dernière révision : janvier 2026. Garantie active jusqu\'en 2028.',
            'specifications' => ['CPU' => 'Intel Xeon Gold 6226R (2x)', 'RAM' => '64GB DDR4', 'Disk' => '2TB SSD NVMe', 'NIC' => '2x 10Gbps'],
            'manager_id'     => $manager->id,
        ]);

        Resource::create([
            'label'          => 'Serveur HP ProLiant DL380 Gen10',
            'category'       => 'Serveur Physique',
            'description'    => 'Serveur bi-processeur pour applications ERP et middleware.',
            'location'       => 'Baie B - Rack 2',
            'status'         => 'available',
            'condition'      => 'dégradé',
            'internal_notes' => 'Un des deux alimentations redondantes en panne. Commande de remplacement en cours.',
            'specifications' => ['CPU' => 'Intel Xeon Silver 4214 (2x)', 'RAM' => '128GB DDR4', 'Disk' => '4TB HDD RAID', 'NIC' => '4x 1Gbps'],
            'manager_id'     => $manager->id,
        ]);

        $ibm = Resource::create([
            'label'          => 'Serveur IBM Power9',
            'category'       => 'Serveur Physique',
            'description'    => 'Serveur RISC haute fiabilité pour charges de travail critiques.',
            'location'       => 'Baie C - Rack 1',
            'status'         => 'maintenance',
            'condition'      => 'critique',
            'internal_notes' => 'Problème de surchauffe détecté. Arrêt préventif en attente d\'intervention.',
            'specifications' => ['CPU' => 'IBM POWER9 (16 cœurs)', 'RAM' => '256GB ECC', 'Disk' => '6TB SAS', 'NIC' => '2x 25Gbps'],
            'manager_id'     => $manager->id,
        ]);

        // --- Machines Virtuelles ---
        Resource::create([
            'label'          => 'VM Ubuntu 22.04 LTS',
            'category'       => 'Machine Virtuelle',
            'description'    => 'Instance virtuelle Linux pour hébergement web et développement.',
            'location'       => 'Cluster VMware - Nœud 1',
            'status'         => 'available',
            'condition'      => 'bon',
            'internal_notes' => 'Snapshots automatiques activés. Quota disque non atteint.',
            'specifications' => ['vCPU' => '4', 'RAM' => '8GB', 'Disk' => '100GB SSD', 'OS' => 'Ubuntu 22.04'],
            'manager_id'     => $manager->id,
        ]);

        Resource::create([
            'label'          => 'VM Windows Server 2022',
            'category'       => 'Machine Virtuelle',
            'description'    => 'Instance Windows pour applications et services Active Directory.',
            'location'       => 'Cluster VMware - Nœud 2',
            'status'         => 'available',
            'condition'      => 'bon',
            'internal_notes' => 'Licences Microsoft valides jusqu\'en 2027.',
            'specifications' => ['vCPU' => '8', 'RAM' => '16GB', 'Disk' => '200GB SSD', 'OS' => 'Windows Server 2022'],
            'manager_id'     => $manager->id,
        ]);

        $centos = Resource::create([
            'label'          => 'VM CentOS 8 - Prod',
            'category'       => 'Machine Virtuelle',
            'description'    => 'Instance de production pour déploiement de microservices Docker.',
            'location'       => 'Cluster KVM - Nœud 3',
            'status'         => 'available',
            'condition'      => 'dégradé',
            'internal_notes' => 'Performances I/O dégradées suite à la croissance des logs. Nettoyage planifié.',
            'specifications' => ['vCPU' => '16', 'RAM' => '32GB', 'Disk' => '500GB NVMe', 'OS' => 'CentOS 8'],
            'manager_id'     => $manager->id,
        ]);

        // --- Stockage ---
        Resource::create([
            'label'          => 'Baie SAN Dell EMC PowerStore',
            'category'       => 'Stockage',
            'description'    => 'Stockage SAN haute performance pour bases de données critiques.',
            'location'       => 'Salle Stockage A',
            'status'         => 'available',
            'condition'      => 'bon',
            'internal_notes' => 'Taux d\'occupation actuel : 62%. Contrat de maintenance actif.',
            'specifications' => ['Capacité' => '50TB', 'Type' => 'NVMe SAN', 'Débit' => '32Gbps FC', 'IOPS' => '1M+'],
            'manager_id'     => $manager->id,
        ]);

        Resource::create([
            'label'          => 'NAS Synology DS3622xs+',
            'category'       => 'Stockage',
            'description'    => 'Serveur NAS pour sauvegarde et partage de fichiers réseau.',
            'location'       => 'Salle Stockage B',
            'status'         => 'available',
            'condition'      => 'bon',
            'internal_notes' => 'Dernier test de restauration réussi le 01/02/2026.',
            'specifications' => ['Capacité' => '12TB', 'Type' => 'NAS RAID 6', 'Protocoles' => 'NFS / SMB / iSCSI', 'RAM' => '16GB'],
            'manager_id'     => $manager->id,
        ]);

        Resource::create([
            'label'          => 'Bande LTO-9 Library',
            'category'       => 'Stockage',
            'description'    => 'Bibliothèque de bandes pour archivage long terme et conformité.',
            'location'       => 'Salle Archives',
            'status'         => 'available',
            'condition'      => 'bon',
            'internal_notes' => '48 slots disponibles. 12 bandes utilisées pour archivage 2025.',
            'specifications' => ['Capacité' => '100TB (compressé)', 'Type' => 'LTO-9', 'Slots' => '48', 'Débit' => '400MB/s'],
            'manager_id'     => $manager->id,
        ]);

        // --- Réseau ---
        Resource::create([
            'label'          => 'Switch Cisco Catalyst 9300',
            'category'       => 'Réseau',
            'description'    => 'Switch cœur de réseau 48 ports pour le sous-réseau Recherche.',
            'location'       => 'Salle Réseau B',
            'status'         => 'available',
            'condition'      => 'bon',
            'internal_notes' => 'Firmware mis à jour en janvier 2026. Configuration VLAN validée.',
            'specifications' => ['Ports' => '48x1Gbps PoE+', 'Uplink' => '4x 10Gbps SFP+', 'Débit' => '208Gbps', 'VLAN' => '4096'],
            'manager_id'     => $manager->id,
        ]);

        Resource::create([
            'label'          => 'Firewall Fortinet FortiGate 200F',
            'category'       => 'Réseau',
            'description'    => 'Pare-feu NGFW pour la sécurité périmétrique et segmentation réseau.',
            'location'       => 'DMZ - Rack Sécurité',
            'status'         => 'available',
            'condition'      => 'bon',
            'internal_notes' => 'Signatures IPS mises à jour automatiquement. Audit de sécurité planifié pour mars 2026.',
            'specifications' => ['Débit Firewall' => '27Gbps', 'VPN' => 'IPsec / SSL', 'Interfaces' => '18x GE + 4x 10GE', 'IPS' => 'Inclus'],
            'manager_id'     => $manager->id,
        ]);

        Resource::create([
            'label'          => 'Routeur Juniper MX204',
            'category'       => 'Réseau',
            'description'    => 'Routeur cœur haute capacité pour interconnexion datacenter et WAN.',
            'location'       => 'Salle Réseau A',
            'status'         => 'available',
            'condition'      => 'bon',
            'internal_notes' => 'BGP sessions stables. Redondance alimentation vérifiée.',
            'specifications' => ['Débit' => '400Gbps', 'Ports' => '4x 100GbE QSFP28', 'Protocoles' => 'BGP / OSPF / MPLS', 'Alimentation' => 'Redondante'],
            'manager_id'     => $manager->id,
        ]);

        // 5. Exemples de Périodes d'Indisponibilité

        // IBM Power9 : Maintenance en cours (active maintenant)
        UnavailabilityPeriod::create([
            'resource_id' => $ibm->id,
            'created_by'  => $manager->id,
            'reason'      => 'Remplacement du système de refroidissement suite à alerte de surchauffe.',
            'type'        => 'maintenance',
            'start_date'  => now()->subDays(2),
            'end_date'    => now()->addDays(5),
        ]);

        // Dell : Maintenance planifiée future
        UnavailabilityPeriod::create([
            'resource_id' => $dell->id,
            'created_by'  => $manager->id,
            'reason'      => 'Mise à jour firmware et contrôle annuel planifié.',
            'type'        => 'maintenance',
            'start_date'  => now()->addDays(10),
            'end_date'    => now()->addDays(11),
        ]);

        // Dell : Maintenance passée (historique)
        UnavailabilityPeriod::create([
            'resource_id' => $dell->id,
            'created_by'  => $manager->id,
            'reason'      => 'Remplacement disque SSD défaillant.',
            'type'        => 'panne',
            'start_date'  => now()->subDays(30),
            'end_date'    => now()->subDays(29),
        ]);

        // CentOS : Période de réservation exclusive passée
        UnavailabilityPeriod::create([
            'resource_id' => $centos->id,
            'created_by'  => $manager->id,
            'reason'      => 'Migration de services vers nouveau cluster Docker Swarm.',
            'type'        => 'réservé',
            'start_date'  => now()->subDays(7),
            'end_date'    => now()->subDays(5),
        ]);
    }
}