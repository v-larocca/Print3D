<?php

class CFollow {

    
    // -------------FOLLOW--------------
   
    public static function creaFollow(int $idFollowed): bool {
        if (!CUser::isLoggato())
            return false;

        $idUser = USession::getSessionElement('id');

        // Un utente non può seguire se stesso
        if ($idUser === $idFollowed)
            return false;

        if (FFollow::exists($idUser, $idFollowed))
            return false;

        $follow = new EFollow($idUser, $idFollowed);
        return FFollow::createObject($follow);
    }

    public static function eliminaFollow(int $idFollowed): bool {
        if (!CUser::isLoggato())
            return false;

        $idUser = USession::getSessionElement('id');
        if (!FFollow::exists($idUser, $idFollowed))
            return false;

        $follow = new EFollow($idUser, $idFollowed);
        return FFollow::deleteObject($follow->getId());
    }

    
    //chiamate dal FrontController, poi reindirizzano
    
    public static function segui(int $idFollowed): void {
        self::creaFollow($idFollowed);
        header('Location: /print3d/User/profile/' . $idFollowed);
        exit;
    }

    public static function smettiDiSeguire(int $idFollowed): void {
        self::eliminaFollow($idFollowed);
        header('Location: /print3d/User/profile/' . $idFollowed);
        exit;
    }

    
    // --------------VERIFICA E CONTEGGIO---------------

    public static function staSeguendo(int $idFollowed): bool {
        if (!CUser::isLoggato())
            return false;

        $idUser = USession::getSessionElement('id');
        return FFollow::exists($idUser, $idFollowed);
    }

    public static function contaFollower(int $idUser): int {
        return FFollow::countFollowers($idUser);
    }

    public static function contaSeguiti(int $idUser): int {
        return FFollow::countFollowing($idUser);
    }

    //
    // ------------------SRECUPERO---------------
    // 
    public static function getFollower(int $idUser): array {
        return FFollow::retrieveFollowers($idUser);
    }

    public static function getFollowed(int $idUser): array {
        return FFollow::retrieveFollowing($idUser);
    }
}
