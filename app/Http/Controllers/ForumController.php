<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    /**
     * Display forum discussions
     */
    public function index()
    {
        $discussions = \App\Models\ForumDiscussion::with(['creator', 'replies.user'])
            ->latest()
            ->paginate(10);

        return view('pages.forum_discussions', compact('discussions'));
    }

    /**
     * Store new discussion
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:Pertanyaan,Konsultasi,Diskusi'
        ]);

        \App\Models\ForumDiscussion::create([
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'user_id' => Auth::id()
        ]);

        return redirect()->route('forum.index')
            ->with('success', 'Diskusi berhasil dibuat.');
    }

    /**
     * Store reply to discussion
     */
    public function storeReply(Request $request, $discussionId)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        \App\Models\ForumReply::create([
            'discussion_id' => $discussionId,
            'user_id' => Auth::id(),
            'content' => $request->content
        ]);

        return redirect()->route('forum.show', $discussionId)
            ->with('success', 'Balasan berhasil ditambahkan.');
    }

    /**
     * Show specific discussion
     */
    public function show($id)
    {
        $discussion = \App\Models\ForumDiscussion::with(['creator', 'replies.user'])
            ->findOrFail($id);

        return view('pages.forum_discussion_detail', compact('discussion'));
    }

    /**
     * Create new discussion form
     */
    public function create()
    {
        return view('pages.forum_create_discussion');
    }
}
