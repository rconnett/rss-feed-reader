<?php

namespace AppBundle\Controller;

use AppBundle\Entity\RSSFeed;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FeedIo\Reader\ReadErrorException;

/**
 * Rssfeed controller.
 * @Route("rss")
 */
class RSSFeedController extends Controller
{
    /**
     * Lists all rSSFeed entities.
     *
     * @Route("/", name="rssfeed_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $rSSFeeds = $em->getRepository('AppBundle:RSSFeed')->findAll();

        return $this->render('rssfeed/index.html.twig', array(
            'rSSFeeds' => $rSSFeeds,
        ));
    }

    /**
     * Creates a new rSSFeed entity.
     *
     * @Route("/new", name="rssfeed_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $rSSFeed = new Rssfeed();
        $form = $this->createForm('AppBundle\Form\RSSFeedType', $rSSFeed);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($rSSFeed);
            $em->flush();

            // Confirm add
            $this->addFlash('success', 'Feed created, thank you');

            return $this->redirectToRoute('rssfeed_show', array('slug' => $rSSFeed->getSlug()));
        }

        return $this->render('rssfeed/new.html.twig', array(
            'rSSFeed' => $rSSFeed,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a rSSFeed entity.
     *
     * @Route("/{slug}", name="rssfeed_show")
     * @Method("GET")
     */
    public function showAction(RSSFeed $rSSFeed)
    {
        $deleteForm = $this->createDeleteForm($rSSFeed);

        $reader = $this->get('feedio');

        try {
            $feed = $reader->read($rSSFeed->getUrl())->getFeed();
        } catch (ReadErrorException $e) {
            $this->addFlash('danger', sprintf('Unable to read the %s rss feed [ %s ]', $rSSFeed->getName(), $rSSFeed->getUrl()));
            return $this->redirectToRoute('rssfeed_index');
        }

        return $this->render('rssfeed/show.html.twig', array(
            'rSSFeed' => $rSSFeed,
            'feed' => $feed,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing rSSFeed entity.
     *
     * @Route("/{slug}/edit", name="rssfeed_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, RSSFeed $rSSFeed)
    {
        $deleteForm = $this->createDeleteForm($rSSFeed);
        $editForm = $this->createForm('AppBundle\Form\RSSFeedType', $rSSFeed);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            // Confirm save
            $this->addFlash('success', 'Feed updated, thank you');

            return $this->redirectToRoute('rssfeed_show', array('slug' => $rSSFeed->getSlug()));
        }

        return $this->render('rssfeed/edit.html.twig', array(
            'rSSFeed' => $rSSFeed,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a rSSFeed entity.
     *
     * @Route("/{slug}", name="rssfeed_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, RSSFeed $rSSFeed)
    {
        $form = $this->createDeleteForm($rSSFeed);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($rSSFeed);
            $em->flush();

            // Confirm delete
            $this->addFlash('success', 'Feed deleted, thank you');
        }

        return $this->redirectToRoute('rssfeed_index');
    }

    /**
     * Creates a form to delete a rSSFeed entity.
     *
     * @param RSSFeed $rSSFeed The rSSFeed entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(RSSFeed $rSSFeed)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('rssfeed_delete', array('slug' => $rSSFeed->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
