<?php
    class Message{
        //Attributs d'un forum
        private ?int $idMessage;
        private ?string $contenu;
        private ?int $nbLikes;
        private ?int $nbDislikes;
        private ?string $pseudo;
        private ?string $photoProfil;
        private ?int $idForum;
        private ?int $idUtilisateur;
        
        //Constructeur de la classe Forum
        public function __construct(?int $idMessage=null, ?string $contenu=null, ?int $nbLikes=null, ?int $nbDislikes=null, ?int $idForum=null, ?int $idUtilisateur=null){
            $this->idMessage = $idMessage;
            $this->contenu = $contenu;
            $this->nbLikes = $nbLikes;
            $this->nbDislikes = $nbDislikes;
            $this->idForum = $idForum;
            $this->idUtilisateur = $idUtilisateur;
        }

        public function getIdMessage(): ?int{
            return $this->idMessage;
        }

        public function setIdMessage(?int $idMessage): void{
            $this->idMessage = $idMessage;
        }
        
        public function getContenu(): ?string{
            return $this->contenu;
        }

        public function setContenu(?string $contenu): void{
            $this->contenu = $contenu;
        }

        public function getNbLikes(): ?int{
            return $this->nbLikes;
        }

        public function setNbLikes(?int $nbLikes): void{
            $this->nbLikes = $nbLikes;
        }

        public function getNbDislikes(): ?int{
            return $this->nbDislikes;
        }

        public function setNbDislikes(?int $nbDislikes): void{
            $this->nbDislikes = $nbDislikes;
        }

        public function getPseudo(): ?string{
            return $this->pseudo;
        }

        public function setPseudo(?string $pseudo): void{
            $this->pseudo = $pseudo;
        }

        public function getPhotoProfil(): ?string{
            return $this->photoProfil;
        }

        public function setPhotoProfil(?string $photoProfil): void{
            $this->photoProfil = $photoProfil;
        }

        public function getIdForum(): ?int{
            return $this->idForum;
        }

        public function setIdForum(?int $idForum): void{
            $this->idForum = $idForum;
        }

        public function getIdUtilisateur(): ?int{
            return $this->idUtilisateur;
        }

        public function setIdUtilisateur(?int $idUtilisateur): void{
            $this->idUtilisateur = $idUtilisateur;
        }
    }
?>