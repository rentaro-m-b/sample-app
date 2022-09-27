<x-app-layout>
     <x-slot name="header">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
               {{ __('Tweets') }}
          </h2>
     </x-slot>

     <div class="py-12">
          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

               <div class="bg-white shadow-sm sm:rounded-lg mb-10">
                    <div class="p-6 flex items-center ">
                         <a href="{{ route('tweets.create') }}">
                              <button class="rounded bg-green-400 py-2 px-3">Tweet</button>
                         </a>
                        @if (session('status'))
                        <div class="text-blue-400 ml-auto">
                            {{ session('status') }}
                        </div>
                        @endif
                    </div>
               </div>
               
               <div class="bg-white shadow-sm sm:rounded-lg ">
                    @foreach ($tweets as $tweet)
                    <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                         <div class="flex flex-col ">
                              <span class="text-xs text-gray-800">{{ $tweet->user->name }}</span>
                              <span class="text-xs text-gray-800">{{ $tweet->updated_at }}</span>
                         </div>
                         <div class="flex flex-col ">
                              <form action="{{ route('tweets.add_bookmark') }}" method="POST">
                                   @csrf
                                   <div class="bg-white shadow-sm sm:rounded-lg mb-10">
                                        <button type="submit" class="rounded bg-blue-400 py-2 px-2">{{ __('add_bookmark') }}</button>
                                        <input type="hidden" value="{{ auth()->id() }}" name="user_id">
                                        <input type="hidden" value="{{ $tweet->id }}" name="tweet_id">
                                   </div>
                              </form>
                              <a href="{{ route('tweets.show', $tweet->id) }}">
                                   <p class="text-md">{{$tweet->contents}}</p>
                              </a>
                         </div>
                         <div>
                              @foreach ($replies as $reply)
                              @if ($tweet->id == $reply->tweet_id)
                              <p class="text-md">{{$reply->contents}}</p>
                              @endif
                              @endforeach
                         </div>
                    </div>
                    @endforeach
               </div>

               <div class="my-5">{{$tweets->links()}}</div>

          </div>
     </div>
</x-app-layout>