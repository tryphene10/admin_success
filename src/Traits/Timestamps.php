<?php

    namespace App\Entity\Traits;

    trait Timestamps{
        /**
         * @ORM\PrePersist
         * @ORM\PreUpdate
         */
        public function updateTimeStamp()
        {
            if ($this->getCreatedAt() === null) {
                $this->setCreatedAt(new \DateTimeImmutable());
            }

                $this->setUpdatedAt(new \DateTimeImmutable());
        }
        public function getCreatedAt(): ?\DateTimeImmutable
        {
            return $this->createdAt;
        }
    
        public function setCreatedAt(\DateTimeImmutable $createdAt): self
        {
            $this->createdAt = $createdAt;
    
            return $this;
        }
    
        public function getUpdatedAt(): ?\DateTimeImmutable
        {
            return $this->updatedAt;
        }
    
        public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
        {
            $this->updatedAt = $updatedAt;
    
            return $this;
        }

    }