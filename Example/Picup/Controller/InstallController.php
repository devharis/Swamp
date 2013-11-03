<?php
namespace Controller;
	class InstallController {
		public function DoInstall(\Model\InstallHandler $install_handler, \View\InstallView $install_view) {
			$ret = $install_view->RenderInstallForm();
			
			if ($install_view->TriedToInstall()) {
				if ($install_handler->CreateUserTable()) {
					if ($install_handler->CreateImageTable()) {
						if ($install_handler->CreateCommentTable()) {
							if ($install_handler->CreateImageRatingTable()) {
								if ($install_handler->AddImageRelations() && $install_handler->AddCommentRelations() && $install_handler->AddImageRatingRelations()) {
									if ($install_view->GetAdminUsername() && $install_view->GetAdminPwd())	{
										$user = new \Model\User();
										$user = $user->Create(1, $install_view->GetAdminUsername(), $install_view->hashPassword($install_view->GetAdminPwd()));
										$install_handler->CreateAdminAccount($user);
									}
									$ret .= $install_view->PrintUserMessage(\View\InstallView::CREATE_SUCCESS);
								}
							} else {
								$ret .= $install_view->PrintUserMessage(\View\InstallView::CREATE_RELATIONS_FAILED);
							}
						} else {
							$ret .= $install_view->PrintUserMessage(\View\InstallView::CREATE_COMMENTTABLE_FAILED);
						}
					} else {
						$ret .= $install_view->PrintUserMessage(\View\InstallView::CREATE_IMAGETABLE_FAILED);
					}
				} else {
					$ret .= $install_view->PrintUserMessage(\View\InstallView::CREATE_USERTABLE_FAILED);
				}	
			}
			
			if ($install_view->TriedToUninstall()) {
				if ($install_handler->DeleteDatabase()) {
					$ret .= $install_view->PrintUserMessage(\View\InstallView::UNINSTALL_SUCCESS);
				} else {
					$ret .= $install_view->PrintUserMessage(\View\InstallView::UNINSTALL_FAILED);					
				}
			}		
			
			return $ret;
		}
	}
?>